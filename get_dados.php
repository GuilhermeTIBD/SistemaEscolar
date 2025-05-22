<?php
include 'conexao.php';

$type = $_GET['type'];

if ($type == "alunos") {
    $sql = "SELECT alunos.id, alunos.nome, alunos.idade, turmas.nome AS turma 
            FROM alunos 
            LEFT JOIN turmas ON alunos.turma_id = turmas.id";
} elseif ($type == "professores") {
    $sql = "SELECT * FROM professores";
} elseif ($type == "turmas") {
    $sql = "SELECT * FROM turmas";
}

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
