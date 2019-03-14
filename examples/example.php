<?php
require 'autoload.php';

use Dotenv\Dotenv;
use Redbox\Imap\Client;

$dotenv = Dotenv::create(__DIR__)
    ->load();

$client = Client::make([
    'host' => getenv('SERVER_HOST'),
    'username' => getenv('SERVER_USERNAME'),
    'password' => getenv('SERVER_PASSWORD'),
    'secure' => getenv('SERVER_SECURE'),
    'port' => getenv('SERVER_PORT'),
    'verbose' => true,
    'debug' => getenv('SERVER_DEBUG'),
]);

$client->login();
//$client->authenticate(); // UNKNOWN COMMAND
//$cap = $client->capability(); // NEED TO PARSE CORRECTLY + Return value wrong
//$client->select('INBOX');
//$client->exemine('INBOX');
//$client->delete('somefolder');
//$client->create('somefolder');
//$client->subscribe('somefolder');
//$client->unsubscribe('somefolder'); // UNCOMFIRMLED TO WORK
//$client->lsub("*"); // NEEDS TO BE PARSED
//$client->list('', '*'); // INCOMPLETE + WHY DOESNT IT SELECT THE SELECTED MAILBOX?
//$client->authenticate();
$client->logout();
