SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS = 0;

-- =========================================================
-- EXCLUSÃO DAS TABELAS EXISTENTES
-- =========================================================

DROP TABLE IF EXISTS `push_inscricoes`;
DROP TABLE IF EXISTS `agendamento_historico`;
DROP TABLE IF EXISTS `agendamentos`;
DROP TABLE IF EXISTS `barbeiro_servicos`;
DROP TABLE IF EXISTS `barbeiros`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `servicos`;
DROP TABLE IF EXISTS `horarios_funcionamento`;
DROP TABLE IF EXISTS `usuarios`;

-- =========================================================
-- TABELA: usuarios
-- =========================================================

CREATE TABLE `usuarios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(150) NOT NULL,
    `senha` VARCHAR(255) NOT NULL,
    `role` ENUM('admin','barbeiro','cliente') NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_usuarios_email` (`email`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- TABELA: clientes
-- =========================================================

CREATE TABLE `clientes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario_id` INT(11) NOT NULL,
    `nome` VARCHAR(100) NOT NULL,
    `telefone` VARCHAR(20) DEFAULT NULL,

    PRIMARY KEY (`id`),
    KEY `idx_clientes_usuario_id` (`usuario_id`),

    CONSTRAINT `fk_clientes_usuario`
        FOREIGN KEY (`usuario_id`)
        REFERENCES `usuarios` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- TABELA: barbeiros
-- =========================================================

CREATE TABLE `barbeiros` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario_id` INT(11) NOT NULL,
    `nome` VARCHAR(100) NOT NULL,
    `especialidade` VARCHAR(100) DEFAULT NULL,
    `foto` VARCHAR(255) DEFAULT NULL,
    `ativo` TINYINT(1) DEFAULT 1,

    PRIMARY KEY (`id`),
    KEY `idx_barbeiros_usuario_id` (`usuario_id`),

    CONSTRAINT `fk_barbeiros_usuario`
        FOREIGN KEY (`usuario_id`)
        REFERENCES `usuarios` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- TABELA: servicos
-- =========================================================

CREATE TABLE `servicos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `descricao` TEXT DEFAULT NULL,
    `preco` DECIMAL(8,2) NOT NULL,
    `duracao_min` INT(11) NOT NULL,

    PRIMARY KEY (`id`)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- TABELA: barbeiro_servicos
-- =========================================================

CREATE TABLE `barbeiro_servicos` (
    `barbeiro_id` INT(11) NOT NULL,
    `servico_id` INT(11) NOT NULL,

    PRIMARY KEY (`barbeiro_id`, `servico_id`),
    KEY `idx_barbeiro_servicos_servico` (`servico_id`),

    CONSTRAINT `fk_barbeiro_servicos_barbeiro`
        FOREIGN KEY (`barbeiro_id`)
        REFERENCES `barbeiros` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT `fk_barbeiro_servicos_servico`
        FOREIGN KEY (`servico_id`)
        REFERENCES `servicos` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- TABELA: horarios_funcionamento
-- =========================================================

CREATE TABLE `horarios_funcionamento` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `dia_semana` TINYINT(1) NOT NULL COMMENT '0=domingo, 1=segunda, ..., 6=sabado',
    `hora_inicio` TIME NOT NULL,
    `hora_fim` TIME NOT NULL,
    `ativo` TINYINT(1) DEFAULT 1,

    PRIMARY KEY (`id`)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- DADOS: horarios_funcionamento
-- =========================================================

INSERT INTO `horarios_funcionamento`
(`dia_semana`, `hora_inicio`, `hora_fim`, `ativo`)
VALUES
(1, '08:00:00', '18:00:00', 1),
(2, '08:00:00', '18:00:00', 1),
(3, '08:00:00', '18:00:00', 1),
(4, '08:00:00', '18:00:00', 1),
(5, '08:00:00', '18:00:00', 1),
(6, '08:00:00', '13:00:00', 1);

-- =========================================================
-- TABELA: agendamentos
-- =========================================================

CREATE TABLE `agendamentos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `cliente_id` INT(11) NOT NULL,
    `barbeiro_id` INT(11) NOT NULL,
    `servico_id` INT(11) NOT NULL,
    `data` DATE NOT NULL,
    `hora` TIME NOT NULL,

    `status` ENUM(
        'pendente',
        'confirmado',
        'concluido',
        'cancelado'
    ) DEFAULT 'pendente',

    `forma_pagamento` ENUM(
        'dinheiro',
        'pix',
        'cartao'
    ) DEFAULT 'dinheiro',

    `status_pagamento` ENUM(
        'pendente',
        'pago',
        'cancelado'
    ) DEFAULT 'pendente',

    `reembolso_solicitado` TINYINT(1) DEFAULT 0,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),

    KEY `idx_agendamentos_cliente` (`cliente_id`),
    KEY `idx_agendamentos_barbeiro` (`barbeiro_id`),
    KEY `idx_agendamentos_servico` (`servico_id`),

    CONSTRAINT `fk_agendamentos_cliente`
        FOREIGN KEY (`cliente_id`)
        REFERENCES `clientes` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT `fk_agendamentos_barbeiro`
        FOREIGN KEY (`barbeiro_id`)
        REFERENCES `barbeiros` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT `fk_agendamentos_servico`
        FOREIGN KEY (`servico_id`)
        REFERENCES `servicos` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- TABELA: agendamento_historico
-- =========================================================

CREATE TABLE `agendamento_historico` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `agendamento_id` INT(11) NOT NULL,
    `usuario_id` INT(11) NOT NULL,

    `acao` ENUM(
        'criado',
        'confirmado',
        'concluido',
        'cancelado',
        'adiado',
        'reembolso_solicitado',
        'reembolso_confirmado'
    ) NOT NULL,

    `detalhes` TEXT DEFAULT NULL,

    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),

    KEY `idx_historico_agendamento` (`agendamento_id`),
    KEY `idx_historico_usuario` (`usuario_id`),

    CONSTRAINT `fk_historico_agendamento`
        FOREIGN KEY (`agendamento_id`)
        REFERENCES `agendamentos` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT `fk_historico_usuario`
        FOREIGN KEY (`usuario_id`)
        REFERENCES `usuarios` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- TABELA: push_inscricoes
-- =========================================================

CREATE TABLE `push_inscricoes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `usuario_id` INT(11) NOT NULL,
    `endpoint` TEXT NOT NULL,
    `p256dh` VARCHAR(255) NOT NULL,
    `auth` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),

    KEY `idx_push_usuario` (`usuario_id`),

    CONSTRAINT `fk_push_usuario`
        FOREIGN KEY (`usuario_id`)
        REFERENCES `usuarios` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =========================================================
-- FINALIZAÇÃO
-- =========================================================

SET FOREIGN_KEY_CHECKS = 1;