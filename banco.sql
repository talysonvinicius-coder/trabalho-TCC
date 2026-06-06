-- ============================================
-- MySQL Workbench Forward Engineering - Parte 1
-- Script unificado com comentários mesclados
-- ============================================

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema bdmusica
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bdmusica` DEFAULT CHARACTER SET utf8mb4;
USE `bdmusica`;

-- -----------------------------------------------------
-- Table `bdmusica`.`artista`
-- Campos:
-- id: Identidade do artista (PK, AUTO_INCREMENT)
-- nome: Nome do artista (UNIQUE)
-- bio: Biografia do artista (opcional)
-- data_criacao: Data de criação do registro
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`artista` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade do artista',
  `nome` VARCHAR(150) NOT NULL COMMENT 'Campo nome do artista',
  `bio` TEXT NULL DEFAULT NULL COMMENT 'Campo biografia do artista',
  `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Campo data de criação do artista',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nome` (`nome` ASC)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`genero`
-- Campos:
-- id: Identidade do gênero (PK, AUTO_INCREMENT)
-- nome: Nome do gênero (UNIQUE)
-- descricao: Descrição opcional do gênero
-- status: Indica se o gênero está ativo (1) ou inativo (0)
-- data_criacao: Data de criação do registro
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`genero` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade do gênero',
  `nome` VARCHAR(100) NOT NULL COMMENT 'Campo nome do gênero',
  `descricao` TEXT NULL DEFAULT NULL COMMENT 'Campo descrição do gênero',
  `status` TINYINT(1) NULL DEFAULT 1 COMMENT 'Campo status do gênero',
  `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Campo data de criação do gênero',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nome` (`nome` ASC)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`album`
-- Campos:
-- id: Identidade do álbum (PK, AUTO_INCREMENT)
-- nome: Nome do álbum
-- artista_id: FK para artista
-- genero_id: FK para gênero (opcional)
-- data_lancamento: Data de lançamento do álbum (opcional)
-- status: Indica se o álbum está ativo (1) ou inativo (0)
-- data_criacao: Data de criação do registro
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`album` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo id do álbum',
  `nome` VARCHAR(150) NOT NULL COMMENT 'Campo nome do álbum',
  `artista_id` INT(11) NOT NULL COMMENT 'Chave estrangeira do artista do álbum',
  `genero_id` INT(11) NULL DEFAULT NULL COMMENT 'Chave estrangeira do gênero do álbum',
  `data_lancamento` DATE NULL DEFAULT NULL COMMENT 'Campo data de lançamento do álbum',
  `status` TINYINT(1) NULL DEFAULT 1 COMMENT 'Campo status do álbum',
  `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Campo data de criação do álbum',
  PRIMARY KEY (`id`),
  INDEX `artista_id` (`artista_id` ASC),
  INDEX `genero_id` (`genero_id` ASC),
  CONSTRAINT `album_ibfk_1`
    FOREIGN KEY (`artista_id`)
    REFERENCES `bdmusica`.`artista` (`id`),
  CONSTRAINT `album_ibfk_2`
    FOREIGN KEY (`genero_id`)
    REFERENCES `bdmusica`.`genero` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`perfil`
-- Campos:
-- id: Identidade do perfil (PK, AUTO_INCREMENT)
-- nome: Nome do perfil (UNIQUE)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`perfil` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade do perfil',
  `nome` VARCHAR(50) NOT NULL COMMENT 'Campo nome do perfil',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nome` (`nome` ASC)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`planos`
-- Campos:
-- id: Identidade do plano (PK, AUTO_INCREMENT)
-- nome: Nome do plano (UNIQUE)
-- descricao: Descrição opcional do plano
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`planos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade do plano',
  `nome` VARCHAR(50) NOT NULL COMMENT 'Campo nome do plano',
  `descricao` TEXT NULL DEFAULT NULL COMMENT 'Campo descrição do plano',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nome` (`nome` ASC)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`usuario`
-- Campos:
-- id: Identidade do usuário (PK, AUTO_INCREMENT)
-- nome: Nome do usuário
-- bio: Biografia do usuário (opcional)
-- status: Status do usuário (1=ativo, 0=inativo)
-- data_criacao: Data de criação do registro
-- perfil_id: FK para perfil
-- plano_id: FK para planos
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`usuario` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade do usuário',
  `nome` VARCHAR(100) NOT NULL COMMENT 'Campo nome do usuário',
  `bio` TEXT NULL DEFAULT NULL COMMENT 'Campo biografia do usuário',
  `status` TINYINT(1) NULL DEFAULT 1 COMMENT 'Campo status do usuário',
  `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Campo data de criação do usuário',
  `perfil_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para perfil',
  `plano_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para planos',
  PRIMARY KEY (`id`),
  INDEX `perfil_id` (`perfil_id` ASC),
  INDEX `plano_id` (`plano_id` ASC),
  INDEX `idx_usuario_nome` (`nome` ASC),
  CONSTRAINT `usuario_ibfk_1`
    FOREIGN KEY (`perfil_id`)
    REFERENCES `bdmusica`.`perfil` (`id`),
  CONSTRAINT `usuario_ibfk_2`
    FOREIGN KEY (`plano_id`)
    REFERENCES `bdmusica`.`planos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARACTER SET=utf8mb4;


-- ============================================
-- MySQL Workbench Forward Engineering - Parte 2
-- Script unificado com comentários mesclados
-- ============================================

-- -----------------------------------------------------
-- Table `bdmusica`.`musicas`
-- Campos:
-- id: Identidade da música (PK, AUTO_INCREMENT)
-- titulo: Título da música
-- artista_id: FK para artista
-- album_id: FK para álbum (opcional)
-- genero_id: FK para gênero (opcional)
-- data_lancamento: Data de lançamento da música (opcional)
-- duracao: Duração da música (opcional)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`musicas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade da música',
  `titulo` VARCHAR(150) NOT NULL COMMENT 'Campo título da música',
  `artista_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para artista',
  `album_id` INT(11) NULL DEFAULT NULL COMMENT 'Chave estrangeira para álbum',
  `genero_id` INT(11) NULL DEFAULT NULL COMMENT 'Chave estrangeira para gênero',
  `data_lancamento` DATE NULL DEFAULT NULL COMMENT 'Campo data de lançamento',
  `duracao` TIME NULL DEFAULT NULL COMMENT 'Campo duração da música',
  PRIMARY KEY (`id`),
  INDEX `artista_id` (`artista_id` ASC),
  INDEX `album_id` (`album_id` ASC),
  INDEX `genero_id` (`genero_id` ASC),
  INDEX `idx_musica_titulo` (`titulo` ASC),
  CONSTRAINT `musicas_ibfk_1`
    FOREIGN KEY (`artista_id`)
    REFERENCES `bdmusica`.`artista` (`id`),
  CONSTRAINT `musicas_ibfk_2`
    FOREIGN KEY (`album_id`)
    REFERENCES `bdmusica`.`album` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `musicas_ibfk_3`
    FOREIGN KEY (`genero_id`)
    REFERENCES `bdmusica`.`genero` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`avaliacoes`
-- Campos:
-- id: Identidade da avaliação (PK, AUTO_INCREMENT)
-- usuario_id: FK para usuário que avaliou
-- musica_id: FK para música avaliada
-- nota: Nota da avaliação (decimal 2,1)
-- comentario: Comentário opcional da avaliação
-- data_avaliacao: Data da avaliação
-- Observação: Usuário só pode avaliar a mesma música uma vez (UNIQUE)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`avaliacoes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade da avaliação',
  `usuario_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para usuário que avaliou',
  `musica_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para música avaliada',
  `nota` DECIMAL(2,1) NOT NULL COMMENT 'Campo nota da avaliação',
  `comentario` TEXT NULL DEFAULT NULL COMMENT 'Campo comentário da avaliação',
  `data_avaliacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Campo data da avaliação',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_avaliacao` (`usuario_id` ASC, `musica_id` ASC),
  INDEX `idx_avaliacao_musica` (`musica_id` ASC),
  CONSTRAINT `avaliacoes_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `bdmusica`.`usuario` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `avaliacoes_ibfk_2`
    FOREIGN KEY (`musica_id`)
    REFERENCES `bdmusica`.`musicas` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`comentarios`
-- Campos:
-- id: Identidade do comentário (PK, AUTO_INCREMENT)
-- usuario_id: FK para usuário que comentou
-- avaliacao_id: FK para avaliação comentada
-- conteudo: Texto do comentário
-- data_comentario: Data do comentário
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`comentarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade do comentário',
  `usuario_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para usuário que comentou',
  `avaliacao_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para avaliação comentada',
  `conteudo` TEXT NOT NULL COMMENT 'Campo conteúdo do comentário',
  `data_comentario` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Campo data do comentário',
  PRIMARY KEY (`id`),
  INDEX `usuario_id` (`usuario_id` ASC),
  INDEX `avaliacao_id` (`avaliacao_id` ASC),
  CONSTRAINT `comentarios_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `bdmusica`.`usuario` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `comentarios_ibfk_2`
    FOREIGN KEY (`avaliacao_id`)
    REFERENCES `bdmusica`.`avaliacoes` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`curtida_avaliacao`
-- Campos:
-- id: Identidade da curtida da avaliação (PK, AUTO_INCREMENT)
-- usuario_id: FK para usuário que curtiu
-- avaliacao_id: FK para avaliação curtida
-- data_curtida: Data da curtida
-- Observação: Um usuário só pode curtir uma avaliação uma vez (UNIQUE)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`curtida_avaliacao` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade da curtida da avaliação',
  `usuario_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para usuário que curtiu',
  `avaliacao_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para avaliação curtida',
  `data_curtida` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Campo data da curtida da avaliação',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `usuario_id` (`usuario_id` ASC, `avaliacao_id` ASC),
  INDEX `avaliacao_id` (`avaliacao_id` ASC),
  CONSTRAINT `curtida_avaliacao_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `bdmusica`.`usuario` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `curtida_avaliacao_ibfk_2`
    FOREIGN KEY (`avaliacao_id`)
    REFERENCES `bdmusica`.`avaliacoes` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`curtida_comentario`
-- Campos:
-- id: Identidade da curtida do comentário (PK, AUTO_INCREMENT)
-- usuario_id: FK para usuário que curtiu
-- comentario_id: FK para comentário curtido
-- data_curtida: Data da curtida
-- Observação: Um usuário só pode curtir um comentário uma vez (UNIQUE)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`curtida_comentario` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade da curtida do comentário',
  `usuario_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para usuário que curtiu',
  `comentario_id` INT(11) NOT NULL COMMENT 'Chave estrangeira para comentário curtido',
  `data_curtida` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Campo data da curtida do comentário',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `usuario_id` (`usuario_id` ASC, `comentario_id` ASC),
  INDEX `comentario_id` (`comentario_id` ASC),
  CONSTRAINT `curtida_comentario_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `bdmusica`.`usuario` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `curtida_comentario_ibfk_2`
    FOREIGN KEY (`comentario_id`)
    REFERENCES `bdmusica`.`comentarios` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;


-- ============================================
-- MySQL Workbench Forward Engineering - Parte 3
-- Script unificado com comentários mesclados
-- ============================================

-- -----------------------------------------------------
-- Table `bdmusica`.`denuncias`
-- Campos:
-- id: Identidade da denúncia (PK, AUTO_INCREMENT)
-- usuario_id: Usuário que realizou a denúncia
-- comentario_id: Comentário denunciado
-- motivo: Motivo da denúncia (texto opcional)
-- status: Estado da denúncia (Pendente, Em Análise, Resolvida, Rejeitada)
-- data_denuncia: Data em que a denúncia foi feita
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`denuncias` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade da denúncia',
  `usuario_id` INT(11) NOT NULL COMMENT 'Usuário que fez a denúncia',
  `comentario_id` INT(11) NOT NULL COMMENT 'Comentário denunciado',
  `motivo` TEXT NULL DEFAULT NULL COMMENT 'Motivo da denúncia',
  `status` ENUM('Pendente', 'Em Analise', 'Resolvida', 'Rejeitada') NULL DEFAULT 'Pendente' COMMENT 'Status da denúncia',
  `data_denuncia` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Data da denúncia',
  PRIMARY KEY (`id`),
  INDEX `usuario_id` (`usuario_id` ASC),
  INDEX `comentario_id` (`comentario_id` ASC),
  CONSTRAINT `denuncias_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `bdmusica`.`usuario` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `denuncias_ibfk_2`
    FOREIGN KEY (`comentario_id`)
    REFERENCES `bdmusica`.`comentarios` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`listas`
-- Campos:
-- id: Identidade da lista (PK, AUTO_INCREMENT)
-- usuario_id: Dono da lista
-- nome: Nome da playlist/lista
-- descricao: Descrição opcional da lista
-- data_criacao: Data de criação
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`listas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade da lista',
  `usuario_id` INT(11) NOT NULL COMMENT 'Usuário dono da lista',
  `nome` VARCHAR(150) NOT NULL COMMENT 'Nome da lista',
  `descricao` TEXT NULL DEFAULT NULL COMMENT 'Descrição da lista',
  `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Data de criação da lista',
  PRIMARY KEY (`id`),
  INDEX `usuario_id` (`usuario_id` ASC),
  CONSTRAINT `listas_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `bdmusica`.`usuario` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`lista_musicas`
-- Tabela de relacionamento N:N entre listas e músicas
-- Campos:
-- id: Identidade do registro
-- lista_id: FK da lista
-- musica_id: FK da música
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`lista_musicas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade da relação lista-música',
  `lista_id` INT(11) NOT NULL COMMENT 'Lista associada',
  `musica_id` INT(11) NOT NULL COMMENT 'Música associada',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_lista_musica` (`lista_id` ASC, `musica_id` ASC),
  INDEX `musica_id` (`musica_id` ASC),
  CONSTRAINT `lista_musicas_ibfk_1`
    FOREIGN KEY (`lista_id`)
    REFERENCES `bdmusica`.`listas` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `lista_musicas_ibfk_2`
    FOREIGN KEY (`musica_id`)
    REFERENCES `bdmusica`.`musicas` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`login`
-- Controle de autenticação do usuário
-- Campos:
-- id: Identidade do login
-- email: Email único do usuário
-- senha: Senha criptografada (hash)
-- ativo: Status do login (1 ativo / 0 inativo)
-- usuario_id: Relacionamento com usuário
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`login` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade do login',
  `email` VARCHAR(150) NOT NULL COMMENT 'Email do usuário',
  `senha` VARCHAR(255) NOT NULL COMMENT 'Senha criptografada do usuário',
  `ativo` TINYINT(1) NULL DEFAULT 1 COMMENT 'Status do login',
  `usuario_id` INT(11) NOT NULL COMMENT 'Usuário vinculado ao login',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email` ASC),
  UNIQUE INDEX `usuario_id` (`usuario_id` ASC),
  CONSTRAINT `login_ibfk_1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `bdmusica`.`usuario` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Table `bdmusica`.`seguidores`
-- Relação de seguidores entre usuários (rede social)
-- Campos:
-- id: Identidade do relacionamento
-- seguidor_id: Usuário que segue
-- seguido_id: Usuário sendo seguido
-- data_seguimento: Data do follow
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bdmusica`.`seguidores` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Campo identidade do seguimento',
  `seguidor_id` INT(11) NOT NULL COMMENT 'Usuário que segue',
  `seguido_id` INT(11) NOT NULL COMMENT 'Usuário sendo seguido',
  `data_seguimento` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Data do seguimento',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uq_seguidor` (`seguidor_id` ASC, `seguido_id` ASC),
  INDEX `seguido_id` (`seguido_id` ASC),
  CONSTRAINT `seguidores_ibfk_1`
    FOREIGN KEY (`seguidor_id`)
    REFERENCES `bdmusica`.`usuario` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `seguidores_ibfk_2`
    FOREIGN KEY (`seguido_id`)
    REFERENCES `bdmusica`.`usuario` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;

-- -----------------------------------------------------
-- Restauração das configurações do MySQL
-- -----------------------------------------------------
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;