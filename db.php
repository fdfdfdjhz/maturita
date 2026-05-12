<?php
$host = 'localhost';
$db   = 'deskovky';
$user = 'root'; // Uprav dle svého nastavení
$pass = '';     // Uprav dle svého nastavení

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Chyba připojení k databázi: " . $e->getMessage());
}
?>