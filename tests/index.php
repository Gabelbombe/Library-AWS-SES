<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

header('Content-type: text/plain');

define  ('APP_HOME', getenv('APP_HOME'));
require APP_HOME . '/vendor/autoload.php';

$ses = New Wrappers\SimpleEmailService\SES(
    getenv('KEY'), getenv('SECRET'), 'email.us-east-1.amazonaws.com'
);


////////////////////////////////////////////

$email = 'jd.daniel@mheducation.com';

echo "Verify emai: $email\n\n";
print_r($ses->verifyEmailAddress($email));
echo "\n\n";

echo "List Verified:\n\n";
print_r($ses->listVerifiedEmailAddresses());
echo "\n";