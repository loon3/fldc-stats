<?php

//get current day
$date = new DateTime();
$date->setTimezone(new DateTimeZone('America/New_York'));
$day = $date->format('Y-m-d');

//download from Stanford as txt
include("download.php");

//wait 30 seconds between download and parsing
sleep(30);

//parse foldingcoin users and save in mysql
include("parse.php");

?>