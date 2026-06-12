-- =============================================================
-- BOLÃO DA COPA DO MUNDO - Script SQL Completo
-- Compatível com MySQL 8.x
-- Database: bolao
-- =============================================================

CREATE DATABASE IF NOT EXISTS bolao
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bolao;

-- -------------------------------------------------------------
-- Tabela: administradores
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS administradores (
    id_admin    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100)  NOT NULL,
    usuario     VARCHAR(50)   NOT NULL UNIQUE,
    senha_hash  VARCHAR(255)  NOT NULL,
    created_at  DATETIME      NULL DEFAULT NULL,
    updated_at  DATETIME      NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabela: jogos
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS jogos (
    id_jogo       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    time_1        VARCHAR(100)   NOT NULL,
    time_2        VARCHAR(100)   NOT NULL,
    data_jogo     DATETIME       NOT NULL,
    valor_palpite DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
    created_at    DATETIME       NULL DEFAULT NULL,
    updated_at    DATETIME       NULL DEFAULT NULL,
    INDEX idx_jogos_data (data_jogo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabela: palpites
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS palpites (
    id_palpite       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_jogo          INT UNSIGNED  NOT NULL,
    nome_completo    VARCHAR(150)  NOT NULL,
    re               VARCHAR(20)   NOT NULL,
    palpite          VARCHAR(10)   NOT NULL,
    status_pagamento ENUM('Pendente','Pago') NOT NULL DEFAULT 'Pendente',
    data_envio       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at       DATETIME      NULL DEFAULT NULL,
    updated_at       DATETIME      NULL DEFAULT NULL,

    CONSTRAINT fk_palpites_jogo
        FOREIGN KEY (id_jogo) REFERENCES jogos(id_jogo)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT uk_palpites_re_jogo UNIQUE (re, id_jogo),

    INDEX idx_palpites_jogo (id_jogo),
    INDEX idx_palpites_status (status_pagamento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Dados iniciais: execute após importar o schema
-- -------------------------------------------------------------
-- php spark db:seed DatabaseSeeder
--
-- Cria administrador (admin / admin123), jogos e palpites de teste.
