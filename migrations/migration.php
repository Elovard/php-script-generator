<?php

include ('core/CustomOrm.php');
use \Core\CustomOrm;

$orm = new CustomOrm();
$conn = $orm::getPdo();
$files = getMigrationFiles($conn);

function getMigrationFiles($conn) {
    $sqlFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/');
    $allFiles = glob($sqlFolder . '*.sql');
    $query = ("SHOW TABLES FROM `participants` LIKE 'versions'");
    $data = $conn->query($query)->rowCount();

    if ($data == 0) {
        echo 'Table VERSIONS is not found. Returning all sql scripts.' . '<br>';
        return $allFiles;
    }

    echo "Table VERSIONS is found, checking..." . '<br>';

    $versionFiles = array();
    $query = ("SELECT name FROM `versions`");
    $data = $conn->query($query)->fetchAll();

    foreach ($data as $row) {
        array_push($versionFiles, $sqlFolder . $row['name']);
    }

    echo 'The following scripts will be executed: ' . '<br>';
    print_r(array_diff($allFiles, $versionFiles)) . '<br>';

    return array_diff($allFiles, $versionFiles);
}

if (empty($files)) {
    echo 'No changes required. DB is in actual state. <br>';
} else {
    echo 'Starting migration... <br>';

    foreach ($files as $file) {
        migrate($conn, $file);
        echo 'Migrated:' . basename($file) . '<br>';
    }

    echo 'Migration has been completed. <br>';
}

function migrate($conn, $file) {
    $queries = file_get_contents($file);
    $strings = explode(';', $queries);

    for ($i = 0; $i < count($strings) - 1; $i++) {
        echo '<h3>Executing: </h3>' . '<i>' . $strings[$i] . '</i>' . '<br>';
        $conn->query($strings[$i]);
    }

    $baseName = basename($file);
    $insertion = ("INSERT INTO versions (name) VALUES (?)");
    $query = $conn->prepare($insertion);
    $query->execute([$baseName]);
}

