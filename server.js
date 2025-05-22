const express = require('express');
const mysql = require('mysql');
const cors = require('cors');
const bodyParser = require('body-parser');

const app = express();
app.use(cors());
app.use(bodyParser.json());

// Configuração do banco de dados
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'gestao_escolar',
    multipleStatements: true // Permite múltiplas queries na mesma execução
});

// Função para gerenciar desconexão e reconexão automática
db.connect(err => {
    if (err) {
        console.error('❌ Erro ao conectar ao banco de dados:', err);
        setTimeout(handleDisconnect, 2000);
    } else {
        console.log('✅ Banco de dados conectado!');
    }
});

db.on('error', err => {
    if (err.code === 'PROTOCOL_CONNECTION_LOST') {
        console.warn('⚠️ Conexão perdida. Tentando reconectar...');
        handleDisconnect();
    } else {
        throw err;
    }
});

function handleDisconnect() {
    db.connect(err => {
        if (err) {
            console.error('❌ Erro ao reconectar:', err);
            setTimeout(handleDisconnect, 2000);
        } else {
            console.log('✅ Reconectado ao banco de dados!');
        }
    });
}

// Middleware para log das requisições
app.use((req, res, next) => {
    console.log(`📥 ${req.method} ${req.url}`);
    next();
});

// Rota para buscar todos os alunos de uma turma específica com suas presenças e renderizar HTML
app.get('/alunosPorTurma', (req, res) => {
    const { turma_id } = req.query;

    if (!turma_id) {
        return res.status(400).send('<h1>Erro: O parâmetro turma_id é obrigatório.</h1>');
    }

    // Consulta ajustada para garantir que estamos buscando alunos de uma turma específica
    let sql = `
        SELECT alunos.id, alunos.nome, turmas.nome AS turma, 
               (SELECT presencas.status 
                FROM presencas 
                WHERE presencas.aluno_id = alunos.id 
                ORDER BY presencas.data DESC 
                LIMIT 1) AS presenca
        FROM alunos
        JOIN turmas ON alunos.turma_id = turmas.id
        WHERE turmas.id = ?
        ORDER BY alunos.nome ASC
    `;

    db.query(sql, [turma_id], (err, results) => {
        if (err) return res.status(500).send('<h1>Erro ao buscar alunos da turma</h1>');

        // Caso não haja alunos para a turma selecionada
        if (results.length === 0) {
            return res.status(404).send('<h1>Nenhum aluno encontrado para essa turma.</h1>');
        }

        // Gerando o conteúdo HTML
        let content = `
            <h2>Alunos da Turma ${results[0].turma}</h2>
            <div style="margin-bottom: 20px;">
                <p><strong>Total de Alunos:</strong> ${results.length}</p>
            </div>
            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Presença</th>
                    </tr>
                </thead>
                <tbody>
        `;

        results.forEach(aluno => {
            content += `
                <tr>
                    <td>${aluno.nome}</td>
                    <td>${aluno.presenca || 'Não registrada'}</td>
                </tr>
            `;
        });

        content += `
                </tbody>
            </table>
        `;

        res.send(content);
    });
});

// Rota para buscar todas as turmas
app.get('/turmas', (req, res) => {
    db.query('SELECT * FROM turmas', (err, results) => {
        if (err) {
            console.error('Erro ao carregar turmas:', err);
            return res.status(500).send('Erro no servidor');
        }
        res.json(results);
    });
});

// Rota para adicionar uma nova turma
app.post('/turmas', (req, res) => {
    const { nome } = req.body;
    if (!nome) return res.status(400).json({ error: 'Nome da turma é obrigatório' });
    db.query('INSERT INTO turmas (nome) VALUES (?)', [nome], (err) => {
        if (err) return res.status(500).json({ error: 'Erro ao adicionar turma', details: err });
        res.json({ message: '✅ Turma adicionada com sucesso!' });
    });
});

// Rota para adicionar um novo aluno
app.post('/alunos', (req, res) => {
    const { nome, idade, turma_id } = req.body;
    if (!nome || !idade || !turma_id) return res.status(400).json({ error: 'Todos os campos são obrigatórios' });
    db.query('INSERT INTO alunos (nome, idade, turma_id) VALUES (?, ?, ?)', [nome, idade, turma_id], (err) => {
        if (err) return res.status(500).json({ error: 'Erro ao adicionar aluno', details: err });
        res.json({ message: '✅ Aluno cadastrado com sucesso!' });
    });
});

// Rota para buscar todas as presenças de uma turma
app.get('/presencasPorTurma', (req, res) => {
    const turmaId = req.query.turma_id;
    
    if (!turmaId) {
        return res.status(400).send('ID da turma é obrigatório');
    }

    // SQL para buscar as presenças com o nome dos alunos
    const sql = `
        SELECT p.id, a.nome AS aluno_nome, p.status, p.data
        FROM presencas p
        JOIN alunos a ON p.aluno_id = a.id
        WHERE a.turma_id = ?
        ORDER BY p.data DESC
    `;
    
    db.query(sql, [turmaId], (err, results) => {
        if (err) {
            console.error('Erro ao buscar presenças:', err);
            return res.status(500).send('Erro no servidor');
        }

        if (results.length === 0) {
            return res.status(404).send('Nenhuma presença encontrada para essa turma');
        }

        res.json(results); // Retorna as presenças como resposta JSON
    });
});

// Rota para registrar a presença de um aluno
app.post('/registrarPresenca', (req, res) => {
    const { aluno_id, status, data } = req.body;

    if (!aluno_id || !status || !data) {
        return res.status(400).send('Todos os campos são obrigatórios');
    }

    // SQL para inserir a presença
    const sql = 'INSERT INTO presencas (aluno_id, status, data) VALUES (?, ?, ?)';
    
    db.query(sql, [aluno_id, status, data], (err, results) => {
        if (err) {
            console.error('Erro ao registrar presença:', err);
            return res.status(500).send('Erro no servidor');
        }

        res.json({ message: 'Presença registrada com sucesso!', presencaId: results.insertId });
    });
});

// Rota para buscar todos os alunos
app.get('/alunos', (req, res) => {
    db.query('SELECT * FROM alunos', (err, results) => {
        if (err) {
            console.error('Erro ao carregar alunos:', err);
            return res.status(500).send('Erro no servidor');
        }
        res.json(results);
    });
});

// Rota para buscar todos os professores
app.get('/professores', (req, res) => {
    db.query('SELECT * FROM professores', (err, results) => {
        if (err) {
            console.error('Erro ao carregar professores:', err);
            return res.status(500).send('Erro no servidor');
        }
        res.json(results);
    });
});

// Endpoint para carregar dados do dashboard
app.get('/dashboard', (req, res) => {
    const query = `
        SELECT 
            (SELECT COUNT(*) FROM turmas) AS turmas,
            (SELECT COUNT(*) FROM alunos) AS alunos,
            (SELECT COUNT(*) FROM professores) AS professores
    `;

    db.query(query, (err, results) => {
        if (err) {
            console.error(err);
            res.status(500).json({ error: 'Erro ao consultar os dados.' });
            return;
        }

        res.json(results[0]);
    });
});

// Iniciar o servidor
app.listen(3000, () => {
    console.log('🚀 Servidor rodando na porta 3000');
});
