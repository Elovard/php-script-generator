<?php

namespace Project\Controllers;

use Core\AffiliateModel;
use Core\Controller;
use Core\ParticipantModel;
use Faker\Factory;
use Project\Models\DbService;

class ScriptController extends Controller {

    protected $title;

    public function start() {
        $this->title = 'script';
        echo 'Starting script...' . '<br>';

        $participant_db = new ParticipantModel();
        $affiliates_db = new AffiliateModel();

        $total_participants_count = $participant_db->getTotalParticipantsCount();

        echo "Checking the amount of participants... Found: $total_participants_count participant(s).<br>";

        if ($total_participants_count == 1) {
            echo 'Generating new participants...' . '<br>';

            $faker = Factory::create();
            $counter = 0;

            while ($counter != 99) {
                $firstname = $faker->firstName;
                $lastname = $faker->lastName;
                $email = $faker->email;
                $shares_amount = generateShares();
                $start_date = generateDate();

                $counter++;

                $affiliates_db->insertRandomAffiliate($firstname, $start_date);
                $participant_db->insertRandomParticipant($firstname, $lastname, $email, $shares_amount, $start_date);

            }

            $counter = 2;

            while ($counter <= 100) {
                $random_affiliate = generateRandomAffiliate();
                $participant_db->setRandomParentToParticipant($counter, $random_affiliate);

                $counter++;
            }

            $counter = 2;

            while ($counter <= 100) {
                $total_shares = $participant_db->calculateTotalShares($counter);

                if ($total_shares >= 1000) {
                    $participant_db->setParticipantPositionToManager($counter);
                } else {
                    $participant_db->setParticipantPositionToNovice($counter);
                }

                $counter++;
            }

            $max_shares = $participant_db->findMaxSharesAmountFromParticipants();
            $participant_db->setParticipantPositionToVicePresident($max_shares);

            echo 'Generation completed!' . '<br>';

        } else {
            echo "Database is not empty!" . '<br>';
            echo "Deleting participants..." . '<br>';

            $participant_db->wipeRecordsFromParticipants();
            $affiliates_db->wipeRecordsFromAffiliates();

            $affiliates_db->refreshAutoIncrementInAffiliates();
            $participant_db->refreshAutoIncrementInParticipants();

            echo 'Deletion completed!';
        }

        return $this->render('script/script');

    }

}