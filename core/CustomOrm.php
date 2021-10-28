<?php

namespace Core;
use \PDO;
include ('project/config/connection.php');

class CustomOrm {

    private static $pdo;

    public function __construct() {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO("mysql:host=" . DB_HOST ."; dbname=" . DB_NAME , DB_USER, DB_PASSWORD);
            } catch (\PDOException $e) {
                echo 'Database error ' . $e->getMessage();
            }
        }
    }

    public static function getPdo(): PDO {
        return self::$pdo;
    }

    public function getById($db_name, $entity_id) {
        $stmt = self::getPdo()->prepare("SELECT * FROM $db_name WHERE entity_id = ?");
        $stmt->execute([$entity_id]);
        return $stmt->fetchAll();
    }

    public function getAll($db_name) {
        $stmt = self::getPdo()->prepare("SELECT * FROM $db_name");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalCount($db_name, $column) {
        $sql_count = self::getPdo()->prepare("SELECT COUNT($column) FROM $db_name");
        $sql_count->execute();
        return $sql_count->fetchColumn();
    }

    public function updateColumn($db_name, $key, $value, $condition, $condition_value) {
        $query_position = self::getPdo()->prepare("UPDATE $db_name SET $key=$value WHERE $condition=$condition_value");
        $query_position->execute();
    }

    public function selectMaxColumnValue($db_name, $column, $condition = null, $condition_value = null) {
        if ($condition != null) {
            $query_max_shares = self::getPdo()->prepare("SELECT MAX($column) FROM $db_name WHERE $condition>$condition_value");
            $query_max_shares->execute();
            return $query_max_shares->fetchColumn();
        } else {
            $query_max_shares = self::getPdo()->prepare("SELECT MAX($column) FROM $db_name");
            $query_max_shares->execute();
            return $query_max_shares->fetchColumn();
        }

    }

    public function deleteRecord($db_name, $column, $condition, $value) {
        $query = "DELETE FROM $db_name WHERE $column $condition $value";
        $stmt = self::getPdo()->prepare($query);
        $stmt->execute();
    }

    public function alterTable($db_name, $auto_increment_value) {
        $query = "ALTER TABLE $db_name AUTO_INCREMENT = $auto_increment_value";
        $stmt = self::getPdo()->prepare($query);
        $stmt->execute();
    }

}