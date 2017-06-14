<?php
//script to get details from fitbit and allow pull as a json feed... great for pebble watch

//1. update mysql credentials as follows

$db_un = '';
$db_pw = '';
$db_db = '';
$calcount = 1700; //fitbit doesn't store this!

$link=mysqli_connect("localhost",$db_un,$db_pw,$db_db);

if (!$link) {
 die('Could not connect: ' . mysqli_error());
}

$data = "SELECT * FROM fitbit WHERE id='1'";
$query=mysqli_fetch_assoc(mysqli_query($link,$data));

//fitbit start
$fitbit = json_decode($query["food"]);
$steps = json_decode($query["value"]);
//$fitbit = json_decode($query["value"]);
//echo $fitbit->summary->steps;
//$steps = $fitbit->summary->steps;
$sleep_time = $sleep->summary->totalMinutesAsleep;
$shours = floor($sleep_time / 60);
$sminutes = $sleep_time % 60;
//fitbit end

$remaining = $calcount - $fitbit->summary->calories;
$targetsteps =  $steps->goals->steps - $steps->summary->steps;
$json["content"] = "$remaining kcal and $targetsteps steps to go today!";
$json["refresh_frequency"] = 5;
$json["vibrate"] = 0;

echo json_encode($json);

?>