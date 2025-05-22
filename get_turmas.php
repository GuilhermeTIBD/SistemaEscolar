<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestao_escolar";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$sql = "SELECT id, nome FROM turmas";
$result = $conn->query($sql);

$turmas = [];
while ($row = $result->fetch_assoc()) {
    $turmas[] = $row;
}

echo json_encode($turmas);
$conn->close();
?>
