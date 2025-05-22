<?php
$host = "localhost";
$user = "root"; // Altere se necessário
$pass = ""; // Altere se necessário
$dbname = "gestao_escolar";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
