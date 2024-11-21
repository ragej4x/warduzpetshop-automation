<?php
$mysqli = new mysqli("localhost", "root", "", "warduz-db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>