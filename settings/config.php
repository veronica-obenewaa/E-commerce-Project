<?php
$servername ="localhost";
$username ="root";
$password ="";
$db_name ="dbforlab";

$conn = mysqli_connect($servername,$username,$password,$db_name);

if(!$conn) {
    error_log("Connection failed: " .mysqli_connect_error());
    die("Connection failed. Please try again later");
}
?>