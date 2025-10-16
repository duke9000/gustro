<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "db_gustro";



if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    die("failed to connect");
}
    // Ensure correct timezone for displaying time
    date_default_timezone_set('Europe/Rome');

    // $desp will contain the current server time (HH:MM:SS)
    $desp = date('H:i:s');

?>