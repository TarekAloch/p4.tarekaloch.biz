<?php
/**
 * User: km
 * Date: 24.10.12
 * Time: 15:45
 */

define('SPELLCHECKER_API_URL', 'http://speller.yandex.net/services/spellservice.json/checkText');


class Checker{

    private $_text;

    function __construct($text){
        $this->_text = $text;
    }


    function check(){
        return array_merge(
            $this->spellCheck(),
            $this->capsCheck(),
            $this->punctuationCheck()
        );
    }

    function spellCheck(){
        $curl = CURL::init(SPELLCHECKER_API_URL)
            ->setHeader(false)
            ->setReturnTransfer(true)
            ->setFollowLocation(false)
            ->setPost(true)
            ->setPostFields('text='.urlencode($this->_text));

        $json = $curl->execute();

        $response = json_decode($json);

        $errors = array();

        foreach ($response as $error){
            $errors[$error->word] = array(
                'type'  => 1,
                's'     => $error->s
            );
        }

        return $errors;

    }


    function punctuationCheck(){

        $errors = array();



        if (preg_match_all('/([!|\.|\,|\?]){4,}/us', $this->_text, $matches)){

            for ($i=0; $i<count($matches[0]);$i++){
                $word = $matches[0][$i];
                $errors[$word] = array(
                    'type'  => 3,
                    's'     => array($matches[1][$i]=='.' ? '...' : $matches[1][$i])
                );
            }
        }


        return $errors;

    }

    function capsCheck(){

        $errors = array();

        if (preg_match_all('|[А-ЯЁ]{4,}|us', $this->_text, $matches)){

            foreach ($matches[0] as $word){
                $errors[$word] = array(
                    'type'  => 2,
                    's'     => array(mb_strtolower($word, 'UTF-8'))
                );
            }
        }


        return $errors;

    }



    private function _splitSentences($str){

        $sentenceRegexp=
            '/'
                ."(?<=[.!?]|[.!?]['\"»])"
                .'(?<!\b[а-яА-Я]\.)'
                .'\s*'
                .'/uis';

        $sentences =  preg_split($sentenceRegexp, $str, -1, PREG_SPLIT_NO_EMPTY);

        return $sentences;
    }

}