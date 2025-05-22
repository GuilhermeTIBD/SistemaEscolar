const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

// Configurar o body-parser para trabalhar com JSON
app.use(bodyParser.json());

// Configuração da conexão com o banco de dados MySQL
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root', // Seu usuário MySQL
    password: '', // Sua senha MySQL (se tiver)
    database: 'gestao_escolar'
});

// Conectar ao banco de dados
db.connect((err) => {
    if (err) {
        console.error('Erro ao conectar ao banco de dados:', err);
        return;
    }
    console.log('Conectado ao banco de dados');
});

// Rota para cadastrar um novo aluno
app.post('/alunos', (req, res) => {
    const { nome, idade, turma_id } = req.body;

    // Verificar se todos os campos obrigatórios foram enviados
    if (!nome || !idade || !turma_id) {
        return res.status(400).json({ message: 'Nome, idade e turma_id são obrigatórios.' });
    }

    // Consultar para garantir que a turma_id exista na tabela turmas
    const turmaQuery = 'SELECT * FROM turmas WHERE id = ?';
    db.query(turmaQuery, [turma_id], (err, results) => {
        if (err) {
            console.error('Erro ao verificar turma:', err);
            return res.status(500).json({ message: 'Erro ao verificar a turma.' });
        }

        if (results.length === 0) {
            return res.status(400).json({ message: 'Turma não encontrada.' });
        }

        // Query para inserir o aluno
        const query = 'INSERT INTO alunos (nome, idade, turma_id) VALUES (?, ?, ?)';
        db.query(query, [nome, idade, turma_id], (err, results) => {
            if (err) {
                console.error('Erro ao cadastrar aluno:', err);
                return res.status(500).json({ message: 'Erro ao cadastrar aluno.' });
            }

            res.status(201).json({ message: 'Aluno cadastrado com sucesso!', alunoId: results.insertId });
        });
    });
});

// Rota para listar todos os alunos
app.get('/alunos', (req, res) => {
    const query = 'SELECT * FROM alunos';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Erro ao buscar alunos:', err);
            return res.status(500).json({ message: 'Erro ao buscar alunos.' });
        }

        res.status(200).json(results);
    });
});

// Iniciar o servidor
app.listen(port, () => {
    console.log(`Servidor rodando em http://localhost:${port}`);
});
