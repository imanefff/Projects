<?php
echo hello ;
session_start();
require "vendor/autoload.php";

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

$version = new Version2X("http://69.30.210.122:3000");
$client = new Client($version);

if (isset($_POST['emails']) && !empty($_POST['emails'])) {

    $emails = $_POST['emails'];

    $emails = trim(preg_replace('/(\r\n)|\n|\r/', ',', $emails));
    echo $emails ;

    $result = popen("python3 " . __DIR__ . "/python/p.py $emails ", "r");

    
    while ($b = fgets($result, 2048)) {

        $client->initialize();
        $client->emit("message", ["resultat" =>  json_decode($b), "uiid" => $_SESSION['uid'], "test"=> $b]);
        $client->close();

        ob_flush();
        flush();
    }

    pclose($result);
}
