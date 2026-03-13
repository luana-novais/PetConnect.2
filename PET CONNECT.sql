CREATE DATABASE projeto_petConnect
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE projeto_petConnect;

-- tabela usuario
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('ADM', 'CLI', 'PRO') NOT NULL DEFAULT 'CLI',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- tabela identificação
CREATE TABLE identificacao (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user BIGINT UNSIGNED NOT NULL,
    tipo_documento ENUM('CNPJ','CPF') NOT NULL DEFAULT 'CPF',
    documento VARCHAR(18) UNIQUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_identificacao_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela especie
CREATE TABLE especie (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipo_pet ENUM('GATO','CACHORRO','PEQUENOS MAMIFEROS','EXOTICOS') NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- tabela raca
CREATE TABLE raca (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50),
    id_especie BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_raca_especie
        FOREIGN KEY (id_especie) REFERENCES especie(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela pets
CREATE TABLE pets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user BIGINT UNSIGNED NOT NULL,
    nome VARCHAR(255) NOT NULL,
    data_nascimento DATE,
    id_especie BIGINT UNSIGNED NOT NULL,
    id_raca BIGINT UNSIGNED NOT NULL,
    peso DECIMAL(5,2),
    sexo ENUM('FEMEA','MACHO') NOT NULL,
    observacoes TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_pets_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_pets_especie
        FOREIGN KEY (id_especie) REFERENCES especie(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_pets_raca
        FOREIGN KEY (id_raca) REFERENCES raca(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela serviços
CREATE TABLE servicos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    valor_servico DECIMAL(10,2),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- tabela agendamentos
CREATE TABLE agendamentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user_cliente BIGINT UNSIGNED NOT NULL,
    id_pet BIGINT UNSIGNED NOT NULL,
    id_user_sitter BIGINT UNSIGNED NOT NULL,
    id_servico BIGINT UNSIGNED NOT NULL,
    data_agendamento DATE,
    hora_inicio TIME,
    hora_fim TIME,
    status ENUM('pendente','confirmado','cancelado','concluido') DEFAULT 'pendente',
    observacoes TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_agendamento_cliente
        FOREIGN KEY (id_user_cliente) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_agendamento_pet
        FOREIGN KEY (id_pet) REFERENCES pets(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_agendamento_sitter
        FOREIGN KEY (id_user_sitter) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_agendamento_servico
        FOREIGN KEY (id_servico) REFERENCES servicos(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela pagamentos
CREATE TABLE pagamentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_agendamento BIGINT UNSIGNED,
    total_servico DECIMAL(10,2),
    metodo_pagamento ENUM('pix','cartao','dinheiro'),
    status_pagamento ENUM('pendente','pago','estornado'),
    data_pagamento DATETIME,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_pagamento_agendamento
        FOREIGN KEY (id_agendamento) REFERENCES agendamentos(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela avaliações
CREATE TABLE avaliacoes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user BIGINT UNSIGNED NOT NULL,
    id_agendamento BIGINT UNSIGNED NOT NULL,
    nota DECIMAL(3,2),
    comentarios TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_avaliacoes_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_avaliacoes_agendamento
        FOREIGN KEY (id_agendamento) REFERENCES agendamentos(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela endereços
CREATE TABLE enderecos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user BIGINT UNSIGNED NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    cidade VARCHAR(255) NOT NULL,
    estado VARCHAR(255) NOT NULL,
    cep VARCHAR(20) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_enderecos_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela disponibilidade
CREATE TABLE disponibilidade (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user BIGINT UNSIGNED,
    dia_semana ENUM('domingo','segunda','terca','quarta','quinta','sexta','sabado'),
    hora_inicio TIME,
    hora_fim TIME,

    CONSTRAINT fk_disponibilidade_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela fotos pets
CREATE TABLE fotos_pets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pet BIGINT UNSIGNED NOT NULL,
    nome_arquivo VARCHAR(255) NOT NULL,
    url_foto VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_fotos_pet
        FOREIGN KEY (id_pet) REFERENCES pets(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela fotos usuarios
CREATE TABLE fotos_usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user BIGINT UNSIGNED NOT NULL,
    nome_arquivo VARCHAR(255) NOT NULL,
    url_foto VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_fotos_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- tabela telefones
CREATE TABLE telefones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user BIGINT UNSIGNED NOT NULL,
    numero VARCHAR(20) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_telefones_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON DELETE CASCADE
);

-- tabela historico servicos
CREATE TABLE historico_servicos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_agendamento BIGINT UNSIGNED NOT NULL,
    descricao TEXT,
    data_evento DATETIME,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_historico_agendamento
        FOREIGN KEY (id_agendamento) REFERENCES agendamentos(id)
        ON DELETE CASCADE
);