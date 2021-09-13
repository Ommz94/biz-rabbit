<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
// Autoload
require $rootPath.'/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount($rootPath.'/creds/fb.json')
    ->withDatabaseUri('https://asalgayan-e932b-default-rtdb.firebaseio.com');

$database = $factory->createDatabase();