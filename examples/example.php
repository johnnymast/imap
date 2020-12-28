<?php
require 'autoload.php';

use Dotenv\Dotenv;
use Redbox\Imap\Client;

// https://tools.ietf.org/html/rfc3501#page-25

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

//Redbox\Imap\Utils\Message::class word niet gebruikt !!

//$client->login();
//$client->authenticate(); // UNKNOWN COMMAND
//$cap = $client->capability(); // NEED TO PARSE CORRECTLY + Return value wrong
//$client->subscribe('INBOX');

$client->examine('INBOX');
//$client->select('INBOX');
//$client->subscribe('INBOX.SubMap');
//$client->unsubscribe('INBOX.SubMap');
//$client->delete('somefolder');
//$client->create('somefolder');
//$client->subscribe('somefolder');
//$client->unsubscribe('somefolder'); // UNCOMFIRMLED TO WORK
//$status = $client->noop();
//$status = $client->capability();
//$status = $client->check();
//$status = $client->close();

//print_r($status);

//$client->rename('somefolder', 'somefolder2');
//$client->lsub("*"); // NEEDS TO BE PARSED
$x = $client->list('', '*'); // INCOMPLETE + WHY DOESNT IT SELECT THE SELECTED MAILBOX?
//$client->authenticate();
$client->logout();

//print_r($x->getParsedData());