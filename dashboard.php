<?php
// Conectar ao banco de dados MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestao_escolar";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta para contar o número de turmas
$sqlTurmas = "SELECT COUNT(*) as total_turmas FROM turmas";
$resultTurmas = $conn->query($sqlTurmas);
$rowTurmas = $resultTurmas->fetch_assoc();
$totalTurmas = $rowTurmas['total_turmas'];

// Consulta para contar o número de alunos
$sqlAlunos = "SELECT COUNT(*) as total_alunos FROM alunos";
$resultAlunos = $conn->query($sqlAlunos);
$rowAlunos = $resultAlunos->fetch_assoc();
$totalAlunos = $rowAlunos['total_alunos'];

// Consulta para contar o número de professores
$sqlProfessores = "SELECT COUNT(*) as total_professores FROM professores";
$resultProfessores = $conn->query($sqlProfessores);
$rowProfessores = $resultProfessores->fetch_assoc();
$totalProfessores = $rowProfessores['total_professores'];

// Preparar os dados para o retorno em formato JSON
$data = [
    'turmas' => $totalTurmas,
    'alunos' => $totalAlunos,
    'professores' => $totalProfessores
];

// Retornar os dados em formato JSON
echo json_encode($data);

// Fechar a conexão
$conn->close();
?>
