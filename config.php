<?php
$host = '127.0.0.1';
$db = 'phplogin';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
session_start();
?>
