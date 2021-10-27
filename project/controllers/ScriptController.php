<?php

namespace Project\Controllers;

use Core\AffiliateModel;
use Core\Controller;
use Core\ParticipantModel;
use Core\Page;
use Project\Services\ScriptService;

class ScriptController extends Controller {

    protected $title;

    public function start(): Page {
        $participant_db = new ParticipantModel();
        $affiliates_db = new AffiliateModel();
        $scriptService = new ScriptService();

        $this->title = 'script';
        echo 'Starting script...' . '<br>';

        $isDbEmpty = $scriptService->isDataBaseEmpty($participant_db);

        if ($isDbEmpty) {
            $scriptService->generateHundredRandomParticipantsAndParents($participant_db, $affiliates_db);

            $scriptService->setRandomParentsToParticipants($participant_db);

            $scriptService->setParticipantsRoles($participant_db);

            echo 'Generation completed!' . '<br>';
        } else {
            echo "Database is not empty!" . '<br>';
            echo "Deleting participants..." . '<br>';

            $scriptService->clearAllRecords($participant_db, $affiliates_db);

            echo 'Deletion completed!';
        }

        return $this->render('script/script');
    }
}