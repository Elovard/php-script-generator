<?php
    namespace Project\Models;
    use \Core\Model;

    class TestDb extends Model {

        public function getParticipantById($id) {
            $stmt = self::getPdo()->prepare("SELECT entity_id, firstname, lastname FROM participants WHERE entity_id = ?");
            $stmt->execute([$id]);
            return $stmt->fetchAll();
        }

        public function getAllParticipants() {
            $stmt = self::getPdo()->prepare("SELECT entity_id, firstname, lastname FROM participants");
            $stmt->execute();
            return $stmt->fetchAll();
        }

    }

