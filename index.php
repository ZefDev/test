<?php

include_once "Config/Database.php";
include_once "Model/People.php";

$database = new Database();
$db = $database->getConnection();

$date = new DateTime('2000-08-01');
$date = $date->format('Y-m-d');

$people1 = new People($db,null ,"Oleg","Ersh",$date,0,"Minsk");

$people2 = new People($db,62 );
var_dump($people2->getStdClass());
$people2->setName("test");
$people2->update();
