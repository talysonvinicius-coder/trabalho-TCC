-- =========================================
-- BANCO
-- =========================================
DROP DATABASE IF EXISTS bdmusica;
CREATE DATABASE IF NOT EXISTS bdmusica
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_general_ci;

USE bdmusica;

-- =========================================
-- PERFIL (permissão)
-- =========================================
CREATE TABLE perfil (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE
);

-- =========================================
-- PLANOS (assinatura)
-- =========================================
CREATE TABLE planos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    descricao TEXT
);

-- =========================================
-- USUARIO
-- =========================================
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    bio TEXT,
    status TINYINT(1) DEFAULT 1,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    perfil_id INT NOT NULL,
    plano_id INT NOT NULL,

    FOREIGN KEY (perfil_id) REFERENCES perfil(id),
    FOREIGN KEY (plano_id) REFERENCES planos(id)
);

-- =========================================
-- LOGIN
-- =========================================
CREATE TABLE login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    usuario_id INT NOT NULL UNIQUE,

    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- =========================================
-- GENERO
-- =========================================
CREATE TABLE genero (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    status TINYINT(1) NOT NULL DEFAULT 1,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- MUSICAS
-- =========================================
CREATE TABLE musicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    artista VARCHAR(150) NOT NULL,
    album VARCHAR(150),
    genero_id INT,
    data_lancamento DATE,
    FOREIGN KEY (genero_id) REFERENCES genero(id)
);

-- =========================================
-- ALBUM
-- =========================================
CREATE TABLE album (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    artista VARCHAR(150) NOT NULL,
    genero VARCHAR(100),
    data_lancamento DATE,
    status TINYINT(1) NOT NULL DEFAULT 1,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- AVALIACOES
-- =========================================
CREATE TABLE avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    musica_id INT NOT NULL,
    nota DECIMAL(2,1),
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (musica_id) REFERENCES musicas(id) ON DELETE CASCADE
);

-- =========================================
-- COMENTARIOS
-- =========================================
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    avaliacao_id INT NOT NULL,
    conteudo TEXT NOT NULL,
    data_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (avaliacao_id) REFERENCES avaliacoes(id) ON DELETE CASCADE
);

-- =========================================
-- CURTIDAS
-- =========================================
CREATE TABLE curtidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    avaliacao_id INT,
    comentario_id INT,
    data_curtiu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (avaliacao_id) REFERENCES avaliacoes(id) ON DELETE CASCADE,
    FOREIGN KEY (comentario_id) REFERENCES comentarios(id) ON DELETE CASCADE
);

-- =========================================
-- SEGUIDORES
-- =========================================
CREATE TABLE seguidores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seguidor_id INT NOT NULL,
    seguido_id INT NOT NULL,
    data_seguimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (seguidor_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (seguido_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- =========================================
-- LISTAS
-- =========================================
CREATE TABLE listas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- =========================================
-- LISTA MUSICAS
-- =========================================
CREATE TABLE lista_musicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lista_id INT NOT NULL,
    musica_id INT NOT NULL,

    FOREIGN KEY (lista_id) REFERENCES listas(id) ON DELETE CASCADE,
    FOREIGN KEY (musica_id) REFERENCES musicas(id) ON DELETE CASCADE
);

-- =========================================
-- DENUNCIAS
-- =========================================
CREATE TABLE denuncias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    comentario_id INT NOT NULL,
    motivo TEXT,
    status VARCHAR(50) DEFAULT 'Pendente',
    data_denuncia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (comentario_id) REFERENCES comentarios(id) ON DELETE CASCADE
);

-- =========================================
-- INSERTS
-- =========================================

-- PERFIS
INSERT INTO perfil (nome) VALUES
('usuario'),
('admin');

-- PLANOS
INSERT INTO planos (nome, descricao) VALUES
('free', 'Plano gratuito com limitações'),
('premium', 'Plano completo sem anúncios');

-- USUARIOS
INSERT INTO usuario (nome, perfil_id, plano_id) VALUES
('João Silva', 1, 1),
('Maria Santos', 1, 2),
('Administrador', 2, 2);

-- LOGIN
INSERT INTO login (email, senha, usuario_id) VALUES
('joao@email.com', MD5('123456'), 1),
('maria@email.com', MD5('123456'), 2),
('admin@email.com', MD5('admin123'), 3);

-- GENEROS
INSERT INTO genero (nome, descricao, status) VALUES
('Pop', 'Gênero Pop', 1),
('Rock', 'Gênero Rock', 1);

-- MUSICAS
INSERT INTO musicas (titulo, artista, album, genero_id) VALUES
('Musica A', 'Artista A', NULL, 1),
('Musica B', 'Artista B', NULL, 2);

-- TESTE
SELECT u.nome, p.nome AS perfil, pl.nome AS plano
FROM usuario u
JOIN perfil p ON u.perfil_id = p.id
JOIN planos pl ON u.plano_id = pl.id;
