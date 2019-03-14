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
//$client->examine('INBOX'); // NOTE DOWN IN CLIENT SELECTED MAILBOX

//
//$client->exemine('INBOX'); // UNSUPPORTED
//$client->create('test_mailbox');
//$client->list('INBOX'); // INCOMPLETE
//$client->authenticate();
$client->logout();
