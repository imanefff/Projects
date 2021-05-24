<?php

session_start();
require "vendor/autoload.php";

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

$version = new Version2X("http://69.30.210.122:3000");
$client = new Client($version);



    $emails = "lorray.ward@videotron.ca:law81323";


    $result = popen("python3 " . __DIR__ . "/python/p.py $emails ", "r");



    

    while ($b = fgets($result, 2048)) {
	var_dump(json_decode($b));
        flush();
	}

