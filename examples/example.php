<?php
require 'autoload.php';

use Redbox\Imap\Client;
use  Dotenv\Dotenv;

$dotenv = Dotenv::create(__DIR__);
$dotenv->load();


/** @var \Redbox\Package\NewClass $instance */
$instance = Redbox\Imap\Client::create([
    'host' => 'imap.ziggo.nl',
    'username' => 'johnnymast@ziggo.nl',

    'port' => '143',
    'verbose' => true,
]);

echo 'Done';