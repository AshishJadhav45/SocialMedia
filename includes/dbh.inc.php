<?php

$serverName = "localhost:3306";
$dBUsername = "root";
$dBPassword = "";
$dBName = "orbit_database";


$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

if (!$conn)
{
    die("Connection failed: ". mysqli_connect_error());
}

