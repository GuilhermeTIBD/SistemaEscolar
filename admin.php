<?php
include('db.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<style>
        /* Resetando margens e paddings */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Fonte base */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f9;
    color: #2c3e50;
    line-height: 1.7;
    padding: 30px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    flex-direction: column;
}

/* Container principal */
.container {
    max-width: 1200px;
    width: 100%;
    margin-top: 50px;
}

/* Cabeçalho */
h1 {
    font-size: 2.6em;
    text-align: center;
    color: #2c3e50;
    margin-bottom: 20px;
    font-weight: 600;
}

/* Títulos das seções */
h2 {
    font-size: 1.8em;
    margin: 30px 0 20px;
    color: #2980b9;
    border-bottom: 3px solid #2980b9;
    padding-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Links */
a {
    color: #2980b9;
    text-decoration: none;
    font-weight: 500;
    display: inline-block;
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #ecf0f1;
    border-radius: 6px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

a:hover {
    background-color: #2980b9;
    color: #fff;
    transform: translateY(-2px);
}

/* Tabelas */
table {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

/* Cabeçalho das tabelas */
thead {
    background-color: #2980b9;
    color: #fff;
    text-align: left;
    text-transform: uppercase;
}

thead th {
    padding: 15px;
    font-weight: 600;
    font-size: 1.1em;
}

/* Corpo da tabela */
tbody td {
    padding: 15px;
    font-size: 1em;
    color: #34495e;
    border-bottom: 1px solid #ecf0f1;
    text-align: left;
    font-weight: 400;
}

/* Efeito de hover nas linhas da tabela */
tbody tr:hover {
    background-color: #ecf0f1;
    cursor: pointer;
    transform: translateX(5px);
    transition: transform 0.3s ease, background-color 0.3s ease;
}

/* Botões de ação nas tabelas */
td a {
    padding: 8px 16px;
    font-size: 1em;
    background-color: #ecf0f1;
    color: #2c3e50;
    border-radius: 5px;
    margin: 5px;
    transition: background-color 0.3s ease, transform 0.3s ease, color 0.3s ease;
}

td a:first-child {
    background-color: #27ae60;
}

td a:first-child:hover {
    background-color: #2ecc71;
    color: #fff;
}

td a:nth-child(2) {
    background-color: #e74c3c;
}

td a:nth-child(2):hover {
    background-color: #c0392b;
    color: #fff;
}

/* Efeito ativo nos links */
a:active, td a:active {
    transform: scale(0.98);
}

/* Responsividade */
@media (max-width: 768px) {
    h1 {
        font-size: 2.2em;
    }

    h2 {
        font-size: 1.6em;
    }

    table {
        font-size: 0.9em;
    }

    td a {
        font-size: 0.9em;
        padding: 5px 12px;
    }

    /* Tabelas se tornam rolagem horizontal */
    table {
        overflow-x: auto;
        display: block;
    }
}

/* Efeito de Fade-in na página */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

body {
    animation: fadeIn 1s ease-out;
}

/* Rodapé */
footer {
    text-align: center;
    padding: 20px;
    margin-top: 50px;
    background-color: #2c3e50;
    color: #fff;
    font-size: 1em;
    border-radius: 6px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Tabela com efeitos de borda */
table {
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

/* Estilo para links de ação */
a, td a {
    transition: all 0.3s ease;
}

a:hover, td a:hover {
    transform: scale(1.05);
}

/* Melhoria do foco em links */
a:focus, td a:focus {
    outline: none;
    border: 2px solid #2980b9;
    box-shadow: 0 0 6px rgba(41, 128, 185, 0.6);
}

    </style>
    <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>
    <h1>Painel de Administração</h1>
    <a href="Index.html">Dashboard</a>
    <h2>Turmas</h2>
    <a href="adicionar.php?tipo=turma">Adicionar Turma</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $pdo->query("SELECT * FROM turmas");
            while ($turma = $query->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$turma['id']}</td>
                        <td>{$turma['nome']}</td>
                        <td>
                            <a href='editar.php?tipo=turma&id={$turma['id']}'>Editar</a> | 
                            <a href='excluir.php?tipo=turma&id={$turma['id']}'>Excluir</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
        
    </table>

    <h2>Professores</h2>
    <a href="adicionar.php?tipo=professor">Adicionar Professor</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Especialidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $pdo->query("SELECT * FROM professores");
            while ($professor = $query->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$professor['id']}</td>
                        <td>{$professor['nome']}</td>
                        <td>{$professor['especialidade']}</td>
                        <td>
                            <a href='editar.php?tipo=professor&id={$professor['id']}'>Editar</a> | 
                            <a href='excluir.php?tipo=professor&id={$professor['id']}'>Excluir</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Alunos</h2>
    <a href="adicionar.php?tipo=aluno">Adicionar Aluno</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Idade</th>
                <th>Turma</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $pdo->query("SELECT alunos.id, alunos.nome, alunos.idade, turmas.nome AS turma FROM alunos JOIN turmas ON alunos.turma_id = turmas.id");
            while ($aluno = $query->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$aluno['id']}</td>
                        <td>{$aluno['nome']}</td>
                        <td>{$aluno['idade']}</td>
                        <td>{$aluno['turma']}</td>
                        <td>
                            <a href='editar.php?tipo=aluno&id={$aluno['id']}'>Editar</a> | 
                            <a href='excluir.php?tipo=aluno&id={$aluno['id']}'>Excluir</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Presenças</h2>
    <a href="adicionar.php?tipo=presenca">Adicionar Presença</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Aluno</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $pdo->query("SELECT presencas.id, alunos.nome AS aluno, presencas.status, presencas.data FROM presencas JOIN alunos ON presencas.aluno_id = alunos.id");
            while ($presenca = $query->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$presenca['id']}</td>
                        <td>{$presenca['aluno']}</td>
                        <td>{$presenca['status']}</td>
                        <td>{$presenca['data']}</td>
                        <td>
                            <a href='editar.php?tipo=presenca&id={$presenca['id']}'>Editar</a> | 
                            <a href='excluir.php?tipo=presenca&id={$presenca['id']}'>Excluir</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
