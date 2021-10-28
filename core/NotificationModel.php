<?php

namespace Core;

const NOTIFICATIONS_DB = 'notifications';

class NotificationModel extends CustomOrm {

    public function setNotifications($participant_id) {
        parent::insertOneValue(NOTIFICATIONS_DB, 'participant_id', $participant_id);
    }

    public function wipeRecordsFromNotifications() {
        parent::deleteAllDataFromTable(NOTIFICATIONS_DB);
    }

    public function refreshAutoIncrementInNotifications() {
        parent::alterTable(NOTIFICATIONS_DB, 0);
    }

}