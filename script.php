<?php

require_once ('database.php');
require_once ('generator.php');
require_once ('vendor/autoload.php');

echo 'Starting script...' . '<br>';

$sql_count = $pdo->prepare("SELECT COUNT(entity_id) FROM participants");
$sql_count->execute();
$total_count = $sql_count->fetchColumn();

echo "Checking the amount of participants... Found $total_count participant(s).<br>";

if ($total_count == 1) {
    echo 'Generating new...' . '<br>';
    echo '__________________________________________' . '<br>';

    $faker = Faker\Factory::create();
    $counter = 0;
    $counter_two = 2;
    $counter_three = 2;
    $parent_id = 0;

    while($counter != 99) {

        $firstname = $faker->firstName;
        $lastname = $faker->lastName;
        $email = $faker->email;
        $shares_amount = generateShares();
        $start_date = generateDate();

        $counter++;

        $insertion_to_affiliates = ("INSERT INTO affiliates (name, start_date) VALUES (?, ?)");
        $query = $pdo->prepare($insertion_to_affiliates);
        $query->execute([$firstname, date('Y-m-d h:s', $start_date)]);

        $insertion = ("INSERT INTO participants (firstname, lastname, email, shares_amount, start_date)
                VALUES (?, ?, ?, ?, ?)");
        $query = $pdo->prepare($insertion);
        $query->execute([$firstname, $lastname, $email, $shares_amount, date('Y-m-d h:s', $start_date)]);
    }

    while ($counter_two <= 100) {
        $random_affiliate = generateRandomAffiliate();

        $query_edit = ("UPDATE participants SET parent_id=$random_affiliate WHERE entity_id=$counter_two");
        $edit = $pdo->prepare($query_edit);
        $edit->execute();

        $counter_two++;
    }

    while ($counter_three <= 100) {
        $query_shares = $pdo->prepare("SELECT SUM(shares_amount) FROM participants WHERE parent_id=$counter_three");
        $query_shares->execute();
        $total_shares = $query_shares->fetchColumn();

        if ($total_shares >= 1000) {
            $query_position = $pdo->prepare("UPDATE participants SET position='manager' WHERE entity_id=$counter_three");
            $query_position->execute();
        } else {
            $query_position = $pdo->prepare("UPDATE participants SET position='novice' WHERE entity_id=$counter_three");
            $query_position->execute();
        }

        $counter_three++;
    }

    $query_max_shares = $pdo->prepare("SELECT MAX(shares_amount) FROM participants WHERE entity_id>1");
    $query_max_shares->execute();
    $max_shares = $query_max_shares->fetchColumn();

    $query_vice = $pdo->prepare("UPDATE participants SET position='vice president' WHERE shares_amount=$max_shares");
    $query_vice->execute();

    echo 'max shares: ' . $max_shares . '<br>';

    echo 'Generation completed!' . '<br>';

} else {
    echo "Found more than one participant!" . '<br>';
    echo "Deleting them..." . '<br>';

    $queries_delete = [
        "DELETE FROM participants WHERE entity_id != 1",
        "DELETE FROM affiliates WHERE id != 0",
        "ALTER TABLE participants AUTO_INCREMENT = 1",
        "ALTER TABLE affiliates AUTO_INCREMENT = 1"
    ];

    foreach ($queries_delete as $query) {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }

    echo 'Deletion complete!';
}
