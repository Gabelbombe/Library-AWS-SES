<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

header('Content-type: text/plain');

define  ('APP_HOME', getenv('APP_HOME'));
$auto = require APP_HOME . '/vendor/autoload.php';

$ses = New Wrappers\SimpleEmailService\SimpleEmailService(
    getenv('KEY'), getenv('SECRET'), 'email-smtp.us-east-1.amazonaws.com'
);

print_r($ses);