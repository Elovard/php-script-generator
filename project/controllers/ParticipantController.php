<?php
    namespace Project\Controllers;
    use Core\Controller;
    use Project\Models\TestDb;

    define('PARTICIPANT_ID_ARRAY', 0);
    define('PARTICIPANT_FIRSTNAME_ARRAY', 1);
    define('PARTICIPANT_LASTNAME_ARRAY', 2);

    class ParticipantController extends Controller {
    public function getOneParticipant($params) {
        $this->title = 'one';
        $db = new TestDb();
        $participant = $db->getParticipantById($params['id']);

        echo "Participant with id " . $params['id'] . ':' .'<br>';
        echo $participant[0][PARTICIPANT_FIRSTNAME_ARRAY] . ' ' . $participant[0][PARTICIPANT_LASTNAME_ARRAY] . '<br>';
        return $this->render('test/show');
    }

    public function getAllParticipants() {
        $this->title = 'all';
        $db = new TestDb();
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
