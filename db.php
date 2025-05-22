<?php
$host = 'localhost'; // ou o seu servidor de banco de dados
$dbname = 'gestao_escolar';
$username = 'root'; // altere para o seu usuário
$password = ''; // altere para a sua senha

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao conectar: " . $e->getMessage();
}
?>
