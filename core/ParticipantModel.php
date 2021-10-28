<?php
	namespace Core;

    const PARTICIPANTS_DB = 'participants';
    const CALCULATE_SHARES_FROM_SECOND_PARTICIPANT = 2;
    const ONE_EMBEDDED_PARTICIPANT = 1;

    class ParticipantModel extends CustomOrm {

        public function getParticipantById($id) {
            return parent::getById(PARTICIPANTS_DB, $id);
        }

        public function getAllParticipants() {
            return parent::getAll(PARTICIPANTS_DB);
        }

        public function getTotalParticipantsCount() {
            return parent::getTotalCount(PARTICIPANTS_DB, 'entity_id');
        }

        public function setParticipantPositionToNovice($id) {
            parent::updateColumn(PARTICIPANTS_DB, 'position', "'novice'", 'entity_id', $id);
        }

        public function setParticipantPositionToManager($id) {
            parent::updateColumn(PARTICIPANTS_DB, 'position', "'manager'", 'entity_id', $id);
        }

        public function setParticipantPositionToVicePresident($shares_amount) {
            parent::updateColumn(PARTICIPANTS_DB, 'position', "'vice president'", 'shares_amount', $shares_amount);
        }

        public function findMaxSharesAmountFromParticipants() {
            return parent::selectMaxColumnValue(PARTICIPANTS_DB,
                'shares_amount', 'entity_id', CALCULATE_SHARES_FROM_SECOND_PARTICIPANT);
        }

        public function wipeRecordsFromParticipants() {
            parent::deleteRecord(PARTICIPANTS_DB, 'entity_id', '!=', ONE_EMBEDDED_PARTICIPANT);
        }

        public function refreshAutoIncrementInParticipants() {
            parent::alterTable(PARTICIPANTS_DB, 1);
        }

        public function setRandomParentToParticipant($participant_id, $affiliate_id) {
            parent::updateColumn(PARTICIPANTS_DB, 'parent_id', $affiliate_id, 'entity_id', $participant_id);
        }

        public function setNotifications($participant_id, $bool_value) {
            parent::updateColumn(PARTICIPANTS_DB, 'notifications_enabled', $bool_value, 'entity_id', $participant_id);
        }

        public function insertRandomParticipant($firstname, $lastname, $email, $shares_amount, $start_date) {
            $insertion = ("INSERT INTO participants (firstname, lastname, email, shares_amount, start_date)
                VALUES (?, ?, ?, ?, ?)");
            $query = parent::getPdo()->prepare($insertion);
            $query->execute([$firstname, $lastname, $email, $shares_amount, date('Y-m-d h:s', $start_date)]);
        }

        public function calculateTotalShares($id) {
            $query_shares = self::getPdo()->prepare("SELECT SUM(shares_amount) FROM participants WHERE parent_id=$id");
            $query_shares->execute();
            return $query_shares->fetchColumn();
        }

    }
