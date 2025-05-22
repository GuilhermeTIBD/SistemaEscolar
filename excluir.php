<?php
include('db.php');

$tipo = $_GET['tipo'];
$id = $_GET['id'];

if ($tipo == 'turma') {
    $query = $pdo->prepare("DELETE FROM turmas WHERE id = :id");
} elseif ($tipo == 'professor') {
    $query = $pdo->prepare("DELETE FROM professores WHERE id = :id");
} elseif ($tipo == 'aluno') {
    $query = $pdo->prepare("DELETE FROM alunos WHERE id = :id");
} elseif ($tipo == 'presenca') {
    $query = $pdo->prepare("DELETE FROM presencas WHERE id = :id");
}

$query->execute([':id' => $id]);
header("Location: admin.php");
?>
