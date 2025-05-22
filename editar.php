<?php
include('db.php');

// Verificar se o tipo e o id foram fornecidos
if (!isset($_GET['tipo']) || !isset($_GET['id'])) {
    die('Tipo ou ID não fornecido.');
}

$tipo = $_GET['tipo'];
$id = $_GET['id'];

// Carregar os dados atuais do registro
if ($tipo == 'aluno') {
    $query = $pdo->prepare("SELECT * FROM alunos WHERE id = :id");
    $query->execute(['id' => $id]);
    $registro = $query->fetch(PDO::FETCH_ASSOC);
} elseif ($tipo == 'professor') {
    $query = $pdo->prepare("SELECT * FROM professores WHERE id = :id");
    $query->execute(['id' => $id]);
    $registro = $query->fetch(PDO::FETCH_ASSOC);
} elseif ($tipo == 'turma') {
    $query = $pdo->prepare("SELECT * FROM turmas WHERE id = :id");
    $query->execute(['id' => $id]);
    $registro = $query->fetch(PDO::FETCH_ASSOC);
} else {
    die('Tipo inválido.');
}

if (!$registro) {
    die('Registro não encontrado.');
}

// Processar o formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($tipo == 'aluno') {
        $nome = $_POST['nome'];
        $idade = $_POST['idade'];
        $turma_id = $_POST['turma_id'];
        $query = $pdo->prepare("UPDATE alunos SET nome = :nome, idade = :idade, turma_id = :turma_id WHERE id = :id");
        $query->execute(['nome' => $nome, 'idade' => $idade, 'turma_id' => $turma_id, 'id' => $id]);
    } elseif ($tipo == 'professor') {
        $nome = $_POST['nome'];
        $especialidade = $_POST['especialidade'];
        $query = $pdo->prepare("UPDATE professores SET nome = :nome, especialidade = :especialidade WHERE id = :id");
        $query->execute(['nome' => $nome, 'especialidade' => $especialidade, 'id' => $id]);
    } elseif ($tipo == 'turma') {
        $nome = $_POST['nome'];
        $query = $pdo->prepare("UPDATE turmas SET nome = :nome WHERE id = :id");
        $query->execute(['nome' => $nome, 'id' => $id]);
    }

    // Redirecionar de volta para a página de administração
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar <?= ucfirst($tipo) ?></title>
</head>
<body>
    <h1>Editar <?= ucfirst($tipo) ?></h1>
    <form method="POST">
        <?php if ($tipo == 'aluno') { ?>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?= $registro['nome'] ?>" required>
            <br>

            <label for="idade">Idade:</label>
            <input type="number" name="idade" value="<?= $registro['idade'] ?>" required>
            <br>

            <label for="turma_id">Turma:</label>
            <select name="turma_id" required>
                <?php
                $turmas = $pdo->query("SELECT * FROM turmas");
                while ($turma = $turmas->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$turma['id']}'" . ($registro['turma_id'] == $turma['id'] ? ' selected' : '') . ">{$turma['nome']}</option>";
                }
                ?>
            </select>
            <br>
        <?php } elseif ($tipo == 'professor') { ?>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?= $registro['nome'] ?>" required>
            <br>

            <label for="especialidade">Especialidade:</label>
            <input type="text" name="especialidade" value="<?= $registro['especialidade'] ?>" required>
            <br>
        <?php } elseif ($tipo == 'turma') { ?>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?= $registro['nome'] ?>" required>
            <br>
        <?php } ?>

        <button type="submit">Salvar</button>
    </form>
    <a href="admin.php">Voltar</a>
</body>
</html>
