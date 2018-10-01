<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);  
$link = mysqli_connect('localhost', 'root', '', 'db_salon');

if (!$link) {
    die('Connect Error: ' . mysqli_connect_error());
}else {
	//echo "connected";
}

?>

