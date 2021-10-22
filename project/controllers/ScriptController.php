<?php

namespace Project\Controllers;

use Core\Controller;
use Faker\Factory;
use Project\Models\ParticipantDb;

class ScriptController extends Controller {

    public function start() {
        $this->title = 'script';
        echo 'Starting script...' . '<br>';
        $db = new ParticipantDb();
        $total_participants_count = $db->getTotalParticipantsCount();

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

                $db->insertIntoRandomAffiliates($firstname, $start_date);

                $db->insertIntoRandomParticipants($firstname, $lastname, $email, $shares_amount, $start_date);

            }

            $counter = 2;

            while ($counter <= 100) {
                $random_affiliate = generateRandomAffiliate();
                $db->insertRandomParentToParticipant($random_affiliate, $counter);

                $counter++;
            }

            $counter = 2;

            while ($counter <= 100) {
                $total_shares = $db->calculateTotalShares($counter);

                if ($total_shares >= 1000) {
                    $db->setManagerPositionToParticipant($counter);
                } else {
                    $db->setNovicePositionToParticipant($counter);
                }

                $counter++;
            }

            $max_shares = $db->getMaxSharesAmountFromParticipants();
            $db->setVicePresidentPositionToParticipant($max_shares);

            echo 'Generation completed!' . '<br>';

        } else {
            echo "Database is not empty!" . '<br>';
            echo "Deleting participants..." . '<br>';

            $db->clearDatabase();

            echo 'Deletion complete!';
        }

        return $this->render('script/script');

    }

}