p4.tarekaloch.biz
=================

Project 4 - Wordblocks:

	Wordblocks is a two-player word building game where the end goal is to earn more points than your opponent by building longer words and taking over the board. 
	Players can "stomp" over their opponent's letters to reduce their score. 
	Each play is checked against a standard oxford english dictionary to validate it is a real word before awarding points.

Features:

	- Ability to play multiple games with multiple people

	- Email-id based game sessions

	- Turn based gameplay

	- Simple, mobile friendly ui that is highly cross-browser compatable

	- Dictionary based backend word validation

	- Ability to delete and create new sessions

	- Ability to Auto-resume running game sessions


Components managed by javascript:

	In this game, the client-side score-keeping and display is managed by javascript.
	The javascript keeps track of scores and the state of the board via html5 "data-" attributes.

Additional info:

	To simulate two player sessions, create a new game in one browser window and then open another browser in "incognito" or "private browsing" mode and log in as the invited player. 
	This will allow you to play against yourself to simulate the multi-player interaction.

	* Note: 
		Inline scripts are used by this webapp but only in places where it is necessary to generate 
		and output dynamic content such as game data etc. The base layout contains all externalized resources.


