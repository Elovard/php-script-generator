<?php
	namespace Core;
    use \PDO;

    class Model {

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

        protected function findOne($query) {

        }

        protected function findMany($query) {

        }

        public static function getPdo(): PDO {
            return self::$pdo;
        }

    }
