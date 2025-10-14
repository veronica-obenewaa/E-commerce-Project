<?php
$servername ="localhost";
$username ="veronica.obenewaa";
$password ="22o15Be+w*a45";
$db_name ="ecommerce_2025A_veronica_obenewaa";

$conn = mysqli_connect($servername,$username,$password,$db_name);

if(!$conn) {
    error_log("Connection failed: " .mysqli_connect_error());
    die("Connection failed. Please try again later");
}
?>