<?php
$host = 'localhost';
$db   = 'boardgame_cafe';
$user = 'root';
$pass = '';  

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("database connect failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
