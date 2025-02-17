<?php
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_DATABASE') ?: 'weather_db';
$username = getenv('DB_USERNAME') ?: 'user';
$password = getenv('DB_PASSWORD') ?: 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

return $pdo;
