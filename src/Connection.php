<?php

require_once 'User.php';
require_once 'Comment.php';
require_once 'Message.php';
require_once 'Tweet.php';
require_once 'config.php';

class Db
{
    private static $instance = NULL;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            self::$instance = new PDO('mysql:host=' . DB_HOST . ';
                dbname=' . DB_NAME, DB_USER, DB_PASSWORD, $pdo_options);


        }
        return self::$instance;
    }
}


