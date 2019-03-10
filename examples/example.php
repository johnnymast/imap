<?php
require 'autoload.php';

use Dotenv\Dotenv;
use Redbox\Imap\Client;

$dotenv = Dotenv::create(__DIR__)->load();

$client = Client::create([
    'host' => getenv('SERVER_HOST'),
    'username' => getenv('SERVER_USERNAME'),
    'password' => getenv('SERVER_PASSWORD'),
    'secure' => getenv('SERVER_SECURE'),
    'port' => getenv('SERVER_PORT'),
    'verbose' => true,
    'debug' => getenv('SERVER_DEBUG'),
]);

//$client->authenticate();
$client->login();
$client->select('INBOX');
$client->list();
$client->logout();
