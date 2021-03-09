<?php
require 'autoload.php';
require 'class.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::create(__DIR__)->load();
//
//$client = Client::create([
//    'host' => getenv('SERVER_HOST'),
//    'username' => getenv('SERVER_USERNAME'),
//    'password' => getenv('SERVER_PASSWORD'),
//    'secure' => getenv('SERVER_SECURE'),
//    'port' => getenv('SERVER_PORT'),
//    'verbose' => true,
//    'debug' => getenv('SERVER_DEBUG'),
//]);

$imap_driver = new imap_driver();

if ($imap_driver->init('tls://'.getenv('SERVER_HOST'), getenv('SERVER_PORT')) === false) {
    echo "init() failed: ".$imap_driver->error."\n";
    exit;
}

$imap_driver->login(getenv('SERVER_USERNAME'), getenv('SERVER_PASSWORD'));

$test = $imap_driver->select_folder('INBOX');
print_r($test);