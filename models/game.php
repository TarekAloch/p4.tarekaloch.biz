<?php

class game extends model_base {

	// 5x5 game board, like in the original game
	public static $board_size = 5;

	public $id;
	public $player1_id, $player1, $player1_tiles, $player1_locked_tiles;
	public $player2_id, $player2, $player2_tiles, $player2_locked_tiles;
	public $letters;
	public $current_player_id, $current_player;
	public $is_deleted = 0;

	public static function get($id) {
		$game = self::_assign_db_row_to_obj(new game, 'games', $id);
		$game->letters = myexplode(',', $game->letters);

		// check deleted status
		if ($game->is_deleted) {
			$game->id = null;
			return self::_assign_db_row_to_obj($game, 'games', 0);
		}

		$game->player1 = player::get($game->player1_id);
		$game->player2 = player::get($game->player2_id);

		$game->player1_tiles = myexplode(',', $game->player1_tiles);
		$game->player2_tiles = myexplode(',', $game->player2_tiles);

		$game->current_player = player::get($game->current_player_id);

		$game->_find_locked_tiles();
		return $game;
	}

	private function _current_turn() {
		return ($this->current_player->id == $this->player1->id) ? 'player1' : 'player2';
	}

	private function _find_locked_tiles() {
		foreach (range(1,2) as $i) {
			$var = spf('player%d_tiles', $i);
			$tiles = $this->$var;
			$locked_tiles = array();
			foreach ($tiles as $tile) {
				$neighbor_tiles = $this->_neighbor_tiles($tile);
				foreach ($neighbor_tiles as $neighbor_tile) {
					if (!in_array($neighbor_tile, $tiles)) {
						continue 2;
					}
				}
				$locked_tiles[] = $tile;
			}
			$var = spf('player%d_locked_tiles', $i);
			$this->$var = $locked_tiles;
		}
	}

	public function is_game_over() {
		// check if game is over (i.e. every tile is used up)
		return count($this->player1_tiles) + count($this->player2_tiles) == pow(self::$board_size, 2);
	}

	private function _determine_winner() {
		return (count($this->player1_tiles) > count($this->player2_tiles)) ? $this->player1 : $this->player2;
	}

	public function did_i_win() {
		return $this->_determine_winner()->id == player::get_current()->id;
	}

	private function _neighbor_tiles($tile) {

		$neighbors = array();
		$n = self::$board_size;
		$s = $n * $n;

		// not a left tile
		if ($tile % $n != 0) {
			$neighbors[] = $tile - 1;
		}

		// not a right tile
		if (($tile+1) % $n != 0) {
			$neighbors[] = $tile + 1;
		}

		// not a top-row tile
		if ($tile >= $n) {
			$neighbors[] = $tile - $n;
		}

		// not a bottom-row tile
		if ($tile < ($s - $n)) {
			$neighbors[] = $tile + $n;
		}
		
		return $neighbors;
	}

	public static function create(player $player) {
		$letters = range('A', 'Z');
		$end = pow(self::$board_size, 2) - 1;
		foreach (range(0, $end) as $i) {
			$table[$i] = array_rand_value($letters);
		}

		db::query('INSERT INTO games (player1_id, current_player_id, letters, created_at) VALUES (%d, %1$d, "%s", now())', $player->id, implode(',', $table));
		return self::get(db::insert_id());
	}

	public function set_player_2($player2_email) {
		player::add($player2_email);
		$this->player2 = player::get_by_email($player2_email);
		db::query('UPDATE games SET player2_id=%d WHERE id=%d', $this->player2->id, $this->id);
		return true;
	}

	private function _get_email_headers(player $player1) {
		// figure out proper email headers
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/plain; charset=iso-8859-1';
		$headers[] = spf("From: %s", $player1->email);
		$headers[] = spf("Return-Path: %s", $player1->email);
		return implode("\r\n", $headers) . "\r\n";
	}

	private function _send_email(player $player1, player $player2, $turn_id, $subject, $body_path) {
		$to = $player2->email;
		$body = str_replace(array('{player1}', '{player2}', '{game_url}'),
							array($player1->email, $player2->email, spf('%s%s/game/turn/%d', BASE_URL, PATH_PREFIX, $turn_id)),
							file_get_contents($body_path));
		return mail($to, $subject, $body, $this->_get_email_headers($player1));
	}

	private function _invite_opponent_by_email(player $player1, player $player2, $turn_id) {
		$subject = spf('%s invited you to a game of WordBlocks!', $player1->email);
		$body_path = './views/email/invite.txt';
		$this->_send_email($player1, $player2, $turn_id, $subject, $body_path);
	}
		
	private function _send_email_notif_on_move(player $player1, player $player2, $turn_id) {
		$subject = spf('%s just played a move on WordBlocks!', $player1->email);
		$body_path = './views/email/make_move.txt';
		$this->_send_email($player1, $player2, $turn_id, $subject, $body_path);
	}

	public function make_move($coords) {
		$coords = myexplode(',', $coords);
		$word = $this->_get_word_from_coords($coords);
		
  		
		if (true !== $error = $this->_validate_word($word)) {
			return $error;
		}

		// players BEFORE the turn is made
		$current_player = $this->current_player;
		$other_player = ($this->_current_turn() == 'player1') ? $this->player2 : $this->player1;

		// need to determine if this is the first MOVE before determining new tile owners (i.e. making the move)
		$is_first_move = $this->_is_first_move();
		$this->_determine_new_tile_owners($coords);

		// save word in db for future checking
		db::query('INSERT INTO words_played (game_id, player_id, word, created_at) VALUES (%d, %d, "%s", now())', $this->id, $current_player->id, $word);
		$turn_id = db::insert_id();

		// flip current player to be the other player
		$this->current_player_id = $other_player->id;
		$this->current_player = $other_player;

		// save new tile owners, and current_player_id
		db::query('UPDATE games SET player1_tiles="%s", player2_tiles="%s", current_player_id=%d WHERE id=%d', implode(',', $this->player1_tiles), implode(',', $this->player2_tiles), $this->current_player_id, $this->id);

		// send emails if applicable
		if ($is_first_move) {
			// player1 always makes the first move
			$this->_invite_opponent_by_email($this->player1, $this->player2, $turn_id);
		} else {
			$this->_send_email_notif_on_move($current_player, $other_player, $turn_id);
		}

		return true;
	}

	private function _is_first_move() {
		return count($this->player1_tiles) == 0 && count($this->player2_tiles) == 0;
	}

	private function _determine_new_tile_owners($coords) {
		// current turn hasn't flipped yet
		if ($this->_current_turn() == 'player1') {
			$new_coords = array_diff($coords, $this->player2_locked_tiles);
			$this->player1_tiles = array_unique(array_merge($this->player1_tiles, $new_coords));
			$this->player2_tiles = array_diff($this->player2_tiles, $new_coords);
		} else {
			$new_coords = array_diff($coords, $this->player1_locked_tiles);
			$this->player2_tiles = array_unique(array_merge($this->player2_tiles, $new_coords));
			$this->player1_tiles = array_diff($this->player1_tiles, $new_coords);
		}
		// re-calc locked tiles since tile ownerships have just changed
		$this->_find_locked_tiles();
	}

	public function is_tile_locked($tile) {
		return in_array($tile, $this->player1_locked_tiles) || in_array($tile, $this->player2_locked_tiles);
	}

	private function _get_word_from_coords($coords) {
		$word = '';
		foreach ($coords as $coord) {
			$word .= $this->letters[$coord];
		}
		return $word;
	}

	private function _is_word_played($word) {
		// mysql LIKE is case insensitive
		return db::has_row('SELECT null FROM words_played WHERE game_id=%d AND word LIKE "%s%%"', $this->id, $word);
	}

	private function _is_valid_word_length($word) {
		return !empty($word) && strlen($word) > 1;
	}

	private function _is_valid_word($word) {
		# 
		/*
		if (IS_DEV) {
			return true;
		}*/
		$cmd = spf('grep -i "^%s$" /usr/share/dict/words', $word);
		$result = shell_exec($cmd);
		return !empty($result);
	}

	private function _validate_word($word) {
		if (empty($word)) {
			return 'Please choose some letters to form a word';
		} elseif (!$this->_is_valid_word_length($word)) {
			return 'Your word must be at least 2 letters long';
		} elseif (!$this->_is_valid_word($word)) {
			return spf('Sorry, your word "%s" could not be found in the WordBlocks dictionary', strtolower($word));
		} elseif ($this->_is_word_played($word)) {
			return 'Sorry, that word or a word containing it has already been played';
		}

		return true;
	}

	public function form_action() {
		return spf(empty($this->player2->id) ? '/game/start/%d' : '/game/move/%d', $this->id);
	}

	public function delete() {
		db::query('UPDATE games SET is_deleted=1 WHERE id=%d', $this->id);
	}

	public function get_tile_state($tile) {
		// if i am player 1
		if (player::get_current()->id == $this->player1->id) {
			if (in_array($tile, $this->player1_tiles)) {
				return 1;
			} elseif (in_array($tile, $this->player2_tiles)) {
				return -1;
			}
		} else { // i am player 2
			if (in_array($tile, $this->player2_tiles)) {
				return 1;
			} elseif (in_array($tile, $this->player1_tiles)) {
				return -1;
			}
		}
		return 0;
	}

	public function get_tile_deltas($tile) {
		$state = $this->get_tile_state($tile);
		if ($this->is_tile_locked($tile) || $state == 1) {
			return array('me' => 0, 'them' => 0);
		} else {
			return array('me' => 1, 'them' => $state);
		}
	}

	public function get_last_word_played() {
		return (string)db::result('SELECT word FROM words_played WHERE game_id=%d ORDER BY id DESC LIMIT 1', $this->id);
	}

	public function get_last_word_ts() {
		return (int)db::result('SELECT UNIX_TIMESTAMP(created_at) FROM words_played WHERE game_id=%d ORDER BY id DESC LIMIT 1', $this->id);
	}

	public function get_scores() {
		$you = (player::get_current()->id == $this->player1->id) ? count($this->player1_tiles) : count($this->player2_tiles);
		$them = (player::get_current()->id == $this->player1->id) ? count($this->player2_tiles) : count($this->player1_tiles);

		$ret = array('you' => $you, 'them' => $them);

		$ret['winning'] = ($you > $them) ? 'you' : 'them';
		$ret['winning'] = ($you == $them) ? '' : $ret['winning'];

		return $ret;
	}

	public static function sort($games) {

		if (count($games) < 2) {
			return $games;
		}

		usort($games, function($a, $b) {
			// classify the game as myturn, your turn, or completed
			foreach (array($a, $b) as $game) {
				if ($game->is_game_over()) {
					$game->state = -1;
				} elseif (player::get_current()->id == $game->current_player->id) {
					$game->state = 1;
				} else {
					$game->state = 0;
				}
			}
			if ($a->state != $b->state) {
				return strcmp($a->state, $b->state);
			} else {
				// return games based on the ts of the last move
				return strcmp($a->get_last_word_ts(), $b->get_last_word_ts());
			}

		});

		return array_reverse($games);
	}

}