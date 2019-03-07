<?php
require 'autoload.php';

use Redbox\Imap\Client;
use  Dotenv\Dotenv;

$dotenv = Dotenv::create(__DIR__)->load();

$client = Client::create([
    'host' => getenv('SERVER_HOST'),
    'username' => getenv('SERVER_USERNAME'),
    'password' => getenv('SERVER_PASSWORD'),
    'secure' => getenv('SERVER_SECURE'),
    'port' => getenv('SERVER_PORT'),
    'verbose' => true,
]);

$client->authenticate();

echo 'Done';