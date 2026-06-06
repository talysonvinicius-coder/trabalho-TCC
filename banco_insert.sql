-- ========================================================
-- INSERÇÃO DOS DADOS
-- ========================================================

-- PERFIS
INSERT IGNORE INTO perfil (id, nome) VALUES
(1, 'usuario'),
(2, 'admin');

-- PLANOS
INSERT IGNORE INTO planos (id, nome, descricao) VALUES
(1, 'free', 'Plano gratuito com limitações'),
(2, 'premium', 'Plano completo sem anúncios');

-- USUÁRIOS
INSERT IGNORE INTO usuario (id, nome, bio, status, data_criacao, perfil_id, plano_id) VALUES
(1,  'João Silva',      NULL,               1, '2026-05-26 23:30:41', 1, 1),
(2,  'Maria Santos',    NULL,               1, '2026-05-26 23:30:41', 1, 2),
(3,  'Administrador',   NULL,               1, '2026-05-26 23:30:41', 2, 2),
(4,  'Maria Santos',    'Rock na veia!',    1, '2026-05-26 23:31:13', 1, 2),
(5,  'Carlos Souza',    'Eclético',         1, '2026-05-26 23:31:13', 1, 1),
(6,  'Ana Paula',       'Jazz lover',       1, '2026-05-26 23:31:13', 1, 2),
(7,  'Bruno Lima',      'Fã de eletrônica', 1, '2026-05-26 23:31:13', 1, 1),
(8,  'Fernanda Dias',   'Indie é vida',     1, '2026-05-26 23:31:13', 1, 2),
(9,  'Lucas Rocha',     'Rap nacional',     1, '2026-05-26 23:31:13', 1, 1),
(10, 'Patrícia Alves',  'Clássicos sempre', 1, '2026-05-26 23:31:13', 1, 2),
(11, 'marcos',          'marcos bio',       1, '2026-05-26 23:44:17', 1, 1),
(12, 'admin',           'bio teste',        1, '2026-05-26 23:44:32', 2, 2);

-- LOGIN
INSERT IGNORE INTO login (id, email, senha, ativo, usuario_id) VALUES
(1,  'joao@email.com',      'e10adc3949ba59abbe56e057f20f883e',                          1, 1),
(2,  'maria@email.com',     'e10adc3949ba59abbe56e057f20f883e',                          1, 2),
(3,  'admin@email.com',     '0192023a7bbd73250516f069df18b500',                          1, 3),
(5,  'carlos@email.com',    '$2y$10$xsuaV3dTVjN8AS/GGRo/MuGU3CTGSv2rPkq2t4H3k9y8adhIHzLCK', 1, 5),
(6,  'ana@email.com',       '$2y$10$xsuaV3dTVjN8AS/GGRo/MuGU3CTGSv2rPkq2t4H3k9y8adhIHzLCK', 1, 6),
(7,  'bruno@email.com',     '$2y$10$xsuaV3dTVjN8AS/GGRo/MuGU3CTGSv2rPkq2t4H3k9y8adhIHzLCK', 1, 7),
(8,  'fernanda@email.com',  '$2y$10$xsuaV3dTVjN8AS/GGRo/MuGU3CTGSv2rPkq2t4H3k9y8adhIHzLCK', 1, 8),
(9,  'lucas@email.com',     '$2y$10$xsuaV3dTVjN8AS/GGRo/MuGU3CTGSv2rPkq2t4H3k9y8adhIHzLCK', 1, 9),
(10, 'patricia@email.com',  '$2y$10$xsuaV3dTVjN8AS/GGRo/MuGU3CTGSv2rPkq2t4H3k9y8adhIHzLCK', 1, 10),
(11, 'marcos@gmail.com',    '$2y$10$TsKKZDPgByXyB9DggbCUZu1WsxpgPp5p21jsuewWtH32nSvA//vxW', 1, 11),
(12, 'admin@gmail.com',     '$2y$10$zzF9MxMACjbzqG3KtvsghObah3Jc60rta20b.uJvsSwdKIiXYL8r2', 1, 12);

-- GÊNEROS
INSERT IGNORE INTO genero (id, nome, descricao, status) VALUES
(1, 'Pop',       'Gênero Pop',        1),
(2, 'Rock',      'Gênero Rock',       1),
(3, 'Jazz',      'Gênero Jazz',       1),
(4, 'Eletrônica','Gênero Eletrônico', 1),
(5, 'Indie',     'Gênero Indie',      1),
(6, 'Rap',       'Gênero Rap',        1),
(7, 'Clássico',  'Música clássica',   1),
(8, 'Samba',     'Samba brasileiro',  1);

-- ARTISTAS
INSERT IGNORE INTO artista (id, nome) VALUES
(1, 'Linkin Park'),
(2, 'The Offspring'),
(3, 'U2'),
(4, 'The Cranberries'),
(5, 'Pitty e Nando Reis'),
(6, 'Nando Reis');

-- ÁLBUNS
INSERT IGNORE INTO album (id, nome, artista_id, genero_id, data_lancamento) VALUES
(1, '(Official Music Video) - Linkin Park', 1, 2, '2026-05-26'),
(2, 'The Offspring',                        2, 2, '2026-05-26'),
(3, 'The Best Of U2',                       3, 2, '2023-03-12'),
(4, 'The Cranberries',                      4, 2, '2022-11-08'),
(5, 'Pitty e Nando Reis',                   5, 2, '2021-07-30'),
(6, 'Acustico MTV',                         6, 2, '2021-05-20');

-- MÚSICAS
INSERT IGNORE INTO musicas (id, titulo, artista_id, album_id, genero_id, data_lancamento) VALUES
(1,  'The Emptiness Machine',    1, 1, 2, '2026-05-26'),
(2,  'The Kids Aren\'t Alright', 2, 2, 2, '2026-05-26'),
(4,  'New Year\'s Day',          3, 3, 2, '2023-03-12'),
(5,  'Zombie',                   4, 4, 2, '2022-11-08'),
(6,  'Por Onde Andei (Ao Vivo)', 5, 5, 2, '2021-07-30'),
(10, 'All Star Azul',            6, 6, 2, '2021-05-20');
