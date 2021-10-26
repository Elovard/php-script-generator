<?php

namespace Project\Controllers;

use Core\Controller;
use Core\ParticipantModel;
use Project\Models\DbService;

define('PARTICIPANT_ID_ARRAY', 0);
define('PARTICIPANT_FIRSTNAME_ARRAY', 1);
define('PARTICIPANT_LASTNAME_ARRAY', 2);

class ParticipantController extends Controller {

    protected $title;

    public function getOneParticipant($params): \Core\Page {
        $this->title = 'one';
        $db = new ParticipantModel();
        $participant = $db->getParticipantById($params['id']);

        echo "Participant with id " . $params['id'] . ':' . '<br>';
        echo $participant[0][PARTICIPANT_FIRSTNAME_ARRAY] . ' ' . $participant[0][PARTICIPANT_LASTNAME_ARRAY] . '<br>';
        return $this->render('test/show');
    }

    public function getAllParticipants(): \Core\Page {
        $this->title = 'all';
        $db = new ParticipantModel();
        $participants = $db->getAllParticipants();

        echo 'All participants: <br>';
        foreach ($participants as $participant) {
            echo $participant[PARTICIPANT_ID_ARRAY] . ' ' .
                $participant[PARTICIPANT_FIRSTNAME_ARRAY] . ' ' .
                $participant[PARTICIPANT_LASTNAME_ARRAY] . '<br>';
        }

        return $this->render('test/show');
    }
}
