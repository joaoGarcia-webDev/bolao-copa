-- =============================================================
-- BOLÃO DA COPA DO MUNDO - Script SQL Completo
-- Compatível com MySQL 8.x
-- =============================================================

CREATE DATABASE IF NOT EXISTS bolao_copa
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bolao_copa;

-- -------------------------------------------------------------
-- Tabela: administradores
-- Armazena os usuários com perfil de administrador do sistema
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS administradores (
    id_admin    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100)  NOT NULL COMMENT 'Nome completo do administrador',
    usuario     VARCHAR(50)   NOT NULL UNIQUE COMMENT 'Login de acesso',
    senha_hash  VARCHAR(255)  NOT NULL COMMENT 'Senha criptografada com password_hash()',
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Administradores do sistema de bolão';

-- -------------------------------------------------------------
-- Tabela: jogos
-- Cadastro dos jogos disponíveis para apostas
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS jogos (
    id_jogo      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    time_1       VARCHAR(100)  NOT NULL COMMENT 'Nome do primeiro time',
    time_2       VARCHAR(100)  NOT NULL COMMENT 'Nome do segundo time',
    data_jogo    DATETIME      NOT NULL COMMENT 'Data e hora de início do jogo',
    valor_palpite DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor cobrado por palpite (R$)',
    created_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Jogos cadastrados para apostas';

-- -------------------------------------------------------------
-- Tabela: palpites
-- Registro dos palpites enviados pelos usuários
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS palpites (
    id_palpite        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_jogo           INT UNSIGNED  NOT NULL COMMENT 'Referência ao jogo apostado',
    nome_completo     VARCHAR(150)  NOT NULL COMMENT 'Nome completo do apostador',
    re                VARCHAR(20)   NOT NULL COMMENT 'RE (Registro do Empregado) do apostador',
    palpite           VARCHAR(10)   NOT NULL COMMENT 'Palpite no formato NxN (ex: 2x1)',
    status_pagamento  ENUM('Pendente','Pago') NOT NULL DEFAULT 'Pendente'
                      COMMENT 'Status do pagamento: Pendente ou Pago',
    data_envio        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
                      COMMENT 'Data e hora do registro do palpite',
    created_at        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Chave estrangeira garantindo integridade referencial
    CONSTRAINT fk_palpites_jogo
        FOREIGN KEY (id_jogo) REFERENCES jogos(id_jogo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Palpites enviados pelos participantes';

-- -------------------------------------------------------------
-- Índices para otimizar consultas frequentes
-- -------------------------------------------------------------
CREATE INDEX idx_palpites_jogo       ON palpites (id_jogo);
CREATE INDEX idx_palpites_status     ON palpites (status_pagamento);
CREATE INDEX idx_palpites_re         ON palpites (re);
CREATE INDEX idx_jogos_data          ON jogos    (data_jogo);

-- -------------------------------------------------------------
-- Seed: Administrador padrão
-- Senha: admin123 (TROQUE EM PRODUÇÃO!)
-- Hash gerado com password_hash('admin123', PASSWORD_BCRYPT)
-- -------------------------------------------------------------
INSERT INTO administradores (nome, usuario, senha_hash) VALUES
('Administrador', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- ATENÇÃO: senha padrão é "admin123" — altere imediatamente após o primeiro acesso

-- =============================================================
-- FIM DO SCRIPT
-- =============================================================
