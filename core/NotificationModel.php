<?php

namespace Core;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

include 'project/utils/phpmailer/PHPMailer.php';
include 'project/utils/phpmailer/Exception.php';
include 'project/utils/phpmailer/SMTP.php';

const NOTIFICATIONS_DB = 'notifications';
const FIRSTNAME_INDEX = 1;
const EMAIL_INDEX = 3;
const POSITION_INDEX = 4;
const SHARES_AMOUNT_INDEX = 5;

class NotificationModel extends CustomOrm {
    private static $sender;

    public function __construct() {
        self::$sender = new PHPMailer();
        self::$sender->isSMTP();
        self::$sender->Host = 'smtp.mailtrap.io';
        self::$sender->SMTPAuth = true;
        self::$sender->Port = 2525;
        self::$sender->Username = getenv('PHPMailer_Username');
        self::$sender->Password = getenv('PHPMailer_Password');
    }

    public static function getSender(): PHPMailer {
        return self::$sender;
    }

    public function setNotifications($participant_id) {
        parent::insertOneValue(NOTIFICATIONS_DB, 'participant_id', $participant_id);
    }

    public function wipeRecordsFromNotifications() {
        parent::deleteAllDataFromTable(NOTIFICATIONS_DB);
    }

    public function refreshAutoIncrementInNotifications() {
        parent::alterTable(NOTIFICATIONS_DB, 0);
    }

    public function sendNotificationAsEmail($participant_id) {
        $template = file_get_contents('project/utils/template.html');
        $result = parent::selectAllFrom(PARTICIPANTS_DB, 'entity_id', $participant_id);

        $email = $result[0][EMAIL_INDEX];

        $participant_params = [
            'name' => $result[0][FIRSTNAME_INDEX],
            'shares' => $result[0][SHARES_AMOUNT_INDEX],
            'position' => $result[0][POSITION_INDEX]
        ];

        foreach($participant_params as $key => $value) {
            $template = str_replace('{{ '.$key.' }}', $value, $template);
        }

        self::$sender->addAddress($email);
        self::$sender->setFrom('test_mail_for_users@gmail.com');
        self::$sender->isHTML(true);
        self::$sender->Body = $template;

        try {
            self::$sender->send();
        } catch (Exception $ex) {
            echo 'Error during sending email: ' . $ex->getMessage();
        }

    }

}