CREATE TABLE alunos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    profissao VARCHAR(255)
);

INSERT INTO alunos (nome, profissao) VALUES ('João', 'Engenheiro'), ('Maria', 'Médica'), ('José', 'Professor');