<?php

namespace Project\Services;

use Core\AffiliateModel;
use \Core\ParticipantModel;
use Faker\Factory;

class ScriptService {
    private const ONE_EMBEDDED_PARTICIPANT = 1;
    private const FIRST_GENERATED_PARTICIPANT = 2;
    private const MAX_AMOUNT_OF_NEW_PARTICIPANTS = 99;
    private const ALL_PARTICIPANTS = 100;
    private const AMOUNT_OF_SHARES_FOR_MANAGERS = 1000;

    public function isDataBaseEmpty(ParticipantModel $participant_db) : bool {
        $total_participants_count = $participant_db->getTotalParticipantsCount();
        echo "Checking the amount of participants... Found: $total_participants_count participant(s).<br>";
        if ($total_participants_count == self::ONE_EMBEDDED_PARTICIPANT) {
            return true;
        } else {
            return false;
        }
    }

    public function generateHundredRandomParticipantsAndParents(ParticipantModel $participant_db, AffiliateModel $affiliates_db) {
        echo 'Generating new participants...' . '<br>';

        $faker = Factory::create();
        $counter = 0;

        while ($counter != self::MAX_AMOUNT_OF_NEW_PARTICIPANTS) {
            $firstname = $faker->firstName;
            $lastname = $faker->lastName;
            $email = $faker->email;
            $shares_amount = generateShares();
            $start_date = generateDate();

            $counter++;

            $affiliates_db->insertRandomAffiliate($firstname, $start_date);
            $participant_db->insertRandomParticipant($firstname, $lastname, $email, $shares_amount, $start_date);
            }
    }

    public function setRandomParentsToParticipants(ParticipantModel $participant_db) {
        $counter = self::FIRST_GENERATED_PARTICIPANT;
        while ($counter <= self::ALL_PARTICIPANTS) {
            $random_affiliate = generateRandomAffiliate();
            $participant_db->setRandomParentToParticipant($counter, $random_affiliate);

            $counter++;
        }
    }

    public function setParticipantsRoles(ParticipantModel $participant_db) {
        $counter = self::FIRST_GENERATED_PARTICIPANT;

        while ($counter <= self::ALL_PARTICIPANTS) {
            $total_shares = $participant_db->calculateTotalShares($counter);

            if ($total_shares >= self::AMOUNT_OF_SHARES_FOR_MANAGERS) {
                $participant_db->setParticipantPositionToManager($counter);
            } else {
                $participant_db->setParticipantPositionToNovice($counter);
            }

            $counter++;
        }

        $max_shares = $participant_db->findMaxSharesAmountFromParticipants();
        $participant_db->setParticipantPositionToVicePresident($max_shares);
    }

    public function clearAllRecords(ParticipantModel $participant_db, AffiliateModel $affiliates_db) {
        $participant_db->wipeRecordsFromParticipants();
        $affiliates_db->wipeRecordsFromAffiliates();

        $affiliates_db->refreshAutoIncrementInAffiliates();
        $participant_db->refreshAutoIncrementInParticipants();
    }



}