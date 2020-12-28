<?php
require 'autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::create(__DIR__)
  ->load();

$host = getenv('SERVER_HOST');
$username = getenv('SERVER_USERNAME');
$password = getenv('SERVER_PASSWORD');


$mbox = imap_open('{'.$host.'/notls}', $username, $password, OP_HALFOPEN)
or die("can't connect: ".imap_last_error());

$list = imap_getmailboxes($mbox, '{'.$host.'}', "*");
if (is_array($list)) {
    print_r($list);
    foreach ($list as $key => $val) {
        echo "($key) ";
        echo imap_utf7_decode($val->name) . ",";
        echo "'" . $val->delimiter . "',";
        echo $val->attributes . "<br />\n";
    }
} else {
    echo "imap_list failed: ".imap_last_error()."\n";
}

imap_close($mbox);