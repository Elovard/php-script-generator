<?php
	use \Core\Route;
	
	return [
		new Route('/main/', 'hello', 'index'),
        new Route('/participants/:id/', 'participant', 'getOneParticipant'),
        new Route('/participants/', 'participant', 'getAllParticipants'),
	];
	
