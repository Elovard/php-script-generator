<?php

    namespace Core;

    const AFFILIATES_DB = 'affiliates';
    const ONE_EMBEDDED_AFFILIATE = 1;

    class AffiliateModel extends CustomOrm {

        public function wipeRecordsFromAffiliates() {
            parent::deleteRecord(AFFILIATES_DB, 'id', '!=', ONE_EMBEDDED_AFFILIATE);
        }

        public function refreshAutoIncrementInAffiliates() {
            parent::alterTable(AFFILIATES_DB, 1);
        }

        public function insertRandomAffiliate($firstname, $start_date) {
            $insertion_to_affiliates = ("INSERT INTO affiliates (name, start_date) VALUES (?, ?)");
            $query = self::getPdo()->prepare($insertion_to_affiliates);
            $query->execute([$firstname, date('Y-m-d h:s', $start_date)]);
        }

    }