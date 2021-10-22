<?php
    namespace Project\Models;
    use \Core\Model;

    class ParticipantDb extends Model {

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

        public function getTotalParticipantsCount() {
            $sql_count = self::getPdo()->prepare("SELECT COUNT(entity_id) FROM participants");
            $sql_count->execute();
            return $sql_count->fetchColumn();
        }

        public function insertIntoRandomAffiliates($firstname, $start_date) {
            $insertion_to_affiliates = ("INSERT INTO affiliates (name, start_date) VALUES (?, ?)");
            $query = self::getPdo()->prepare($insertion_to_affiliates);
            $query->execute([$firstname, date('Y-m-d h:s', $start_date)]);
        }

        public function insertIntoRandomParticipants($firstname, $lastname, $email, $shares_amount, $start_date) {
            $insertion = ("INSERT INTO participants (firstname, lastname, email, shares_amount, start_date)
                VALUES (?, ?, ?, ?, ?)");
            $query = self::getPdo()->prepare($insertion);
            $query->execute([$firstname, $lastname, $email, $shares_amount, date('Y-m-d h:s', $start_date)]);
        }

        public function insertRandomParentToParticipant($random_affiliate, $id) {
            $query_edit = ("UPDATE participants SET parent_id=$random_affiliate WHERE entity_id=$id");
            $edit = self::getPdo()->prepare($query_edit);
            $edit->execute();
        }

        public function calculateTotalShares($id) {
            $query_shares = self::getPdo()->prepare("SELECT SUM(shares_amount) FROM participants WHERE parent_id=$id");
            $query_shares->execute();
            return $query_shares->fetchColumn();
        }

        public function setManagerPositionToParticipant($id) {
            $query_position = self::getPdo()->prepare("UPDATE participants SET position='manager' WHERE entity_id=$id");
            $query_position->execute();
        }

        public function setNovicePositionToParticipant($id) {
            $query_position = self::getPdo()->prepare("UPDATE participants SET position='novice' WHERE entity_id=$id");
            $query_position->execute();
        }

        public function setVicePresidentPositionToParticipant($shares_amount) {
            $query_vice = self::getPdo()->prepare("UPDATE participants SET position='vice president' WHERE shares_amount=$shares_amount");
            $query_vice->execute();
        }

        public function getMaxSharesAmountFromParticipants() {
            $query_max_shares = self::getPdo()->prepare("SELECT MAX(shares_amount) FROM participants WHERE entity_id>2");
            $query_max_shares->execute();
            return $query_max_shares->fetchColumn();
        }

        public function clearDatabase() {
            $queries_delete = [
                "DELETE FROM participants WHERE entity_id != 1",
                "DELETE FROM affiliates WHERE id != 0",
                "ALTER TABLE participants AUTO_INCREMENT = 1",
                "ALTER TABLE affiliates AUTO_INCREMENT = 1"
            ];

            foreach ($queries_delete as $query) {
                $stmt = self::getPdo()->prepare($query);
                $stmt->execute();
            }
        }
    }

