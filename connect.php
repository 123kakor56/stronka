<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bsi_projekty";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
