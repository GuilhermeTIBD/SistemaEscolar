<?php
// Exibindo erros para ajudar na depuração
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestao_escolar";

// Criando conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Recebendo o ID da turma via GET
$turma_id = $_GET['turma_id'];

// Verificando se o ID da turma foi passado corretamente e é numérico
if (!isset($turma_id) || !is_numeric($turma_id)) {
    http_response_code(400);  // Retorna erro 400 (Bad Request)
    die("Erro: Turma ID inválido.");
}

// Preparando a consulta SQL para buscar os alunos pela turma_id
$sql = "SELECT id, nome FROM alunos WHERE turma_id = ?";
$stmt = $conn->prepare($sql);

// Verificando se a consulta foi preparada corretamente
if ($stmt === false) {
    http_response_code(500);  // Retorna erro 500 (Internal Server Error)
    die("Erro na preparação da consulta: " . $conn->error);
}

// Ligando o parâmetro da consulta
$stmt->bind_param("i", $turma_id);
$stmt->execute();
$result = $stmt->get_result();

// Verificando se a execução da consulta foi bem-sucedida
if ($result === false) {
    http_response_code(500);  // Retorna erro 500 (Internal Server Error)
    die("Erro na execução da consulta: " . $conn->error);
}

// Criando um array para armazenar os alunos
$alunos = [];
while ($row = $result->fetch_assoc()) {
    $alunos[] = $row;
}

// Verificando se há alunos
if (empty($alunos)) {
    echo json_encode([]);  // Se não houver alunos, retorna um array vazio
} else {
    echo json_encode($alunos);  // Se houver alunos, retorna os dados dos alunos
}

// Fechando a conexão com o banco de dados
$conn->close();
?>
