<?php
include 'conexao.php';

$type = $_POST['type'];

if ($type == "aluno") {
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $turma_id = $_POST['turma_id'];
    $sql = "INSERT INTO alunos (nome, idade, turma_id) VALUES ('$nome', $idade, $turma_id)";
} elseif ($type == "professor") {
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];
    $sql = "INSERT INTO professores (nome, especialidade) VALUES ('$nome', '$especialidade')";
} elseif ($type == "turma") {
    $nome = $_POST['nome'];
    $sql = "INSERT INTO turmas (nome) VALUES ('$nome')";
}

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}
?>
