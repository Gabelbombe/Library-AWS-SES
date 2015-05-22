<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

define  ('APP_HOME', getenv('APP_HOME'));
$auto = require APP_HOME . '/vendor/autoload.php';

$ses = New Wrappers\SimpleEmailService\SES(
    getenv('KEY'), getenv('SECRET'), 'email.us-east-1.amazonaws.com'
);

print_r($ses->verifyEmailAddress('jd.daniel@mheducation.com'));