<?php

define  ('APP_HOME', getenv('APP_HOME'));
require APP_HOME . '/vendor/autoload.php';

$sns = New \Wrappers\Aws\SimpleEmailService(
    getenv('KEY'),
    getenv('PEM')
);

print_r($sns);