<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Presença</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-color: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            width: 100%;
            max-width: 700px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #333;
        }
        h2 {
            color: #3498db;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }
        label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
            color: #555;
        }
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 25px;
            background-color: #fafafa;
            transition: 0.3s ease;
        }
        select:focus {
            outline: none;
            border-color: #3498db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s ease;
        }
        .btn-presente {
            background-color: #2ecc71;
            color: white;
        }
        .btn-ausente {
            background-color: #e74c3c;
            color: white;
        }
        .btn-atestado {
            background-color: #f39c12;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .btn-back {
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            font-weight: bold;
        }
        .btn-back:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro de Presença</h2>
        <label for="turma">Selecione a Turma:</label>
        <select id="turma" onchange="carregarAlunos()">
            <option value="">Selecione</option>
            <?php
                $conn = new mysqli("localhost", "root", "", "gestao_escolar");
                if ($conn->connect_error) {
                    die("Erro na conexão: " . $conn->connect_error);
                }
                $sql = "SELECT id, nome FROM turmas";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["nome"] . "</option>";
                }
                $conn->close();
            ?>
        </select>

        <table id="tabela-alunos">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Presença</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <a href="index.html" class="btn-back">Voltar ao Dashboard</a>
    </div>

    <script>
       function carregarAlunos() {
    let turmaId = $("#turma").val();
    if (!turmaId) return;

    $.get("get_alunos.php?turma_id=" + turmaId, function(data) {
        console.log("Resposta do servidor:", data);  // Verifique a resposta no console
        let alunos = JSON.parse(data);
        let tbody = "";
        if (alunos.length === 0) {
            tbody = "<tr><td colspan='2'>Nenhum aluno encontrado</td></tr>";
        } else {
            alunos.forEach(aluno => {
                tbody += `<tr>
                    <td>${aluno.nome}</td>
                    <td class="btn-group">
                        <button class="btn btn-presente" onclick="registrarPresenca(${aluno.id}, 'presente')">✔ Presente</button>
                        <button class="btn btn-ausente" onclick="registrarPresenca(${aluno.id}, 'ausente')">✖ Ausente</button>
                        <button class="btn btn-atestado" onclick="registrarPresenca(${aluno.id}, 'atestado')">📄 Atestado</button>
                    </td>
                </tr>`;
            });
        }
        $("#tabela-alunos tbody").html(tbody);
    });
}
$.get("get_alunos.php?turma_id=1", function(response) { console.log(response); });

function registrarPresenca(alunoId, status) {
    $.post("registrar_presenca.php", { aluno_id: alunoId, status: status }, function(resposta) {
        console.log("Servidor respondeu:", resposta); // Verificar resposta no console
        alert(resposta); // Mostrar mensagem de sucesso ou erro
    });
}

    </script>
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
</body>
</html>
