<?php
	use \Core\Route;
	
	return [
		new Route('/', 'home', 'index'),
        new Route('/participants/:id', 'participant', 'getOneParticipant'),
        new Route('/participants', 'participant', 'getAllParticipants'),
        new Route('/script', 'script', 'start')
	];
	
