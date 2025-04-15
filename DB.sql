CREATE TABLE utente(
    username VARCHAR(20) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    spot_interval INT DEFAULT 1,
    spot_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE remember_tokens(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    token VARCHAR(255) NOT NULL,
    token_expires DATETIME NOT NULL,
    device_info VARCHAR(255),
    FOREIGN KEY (username) REFERENCES utente(username) ON DELETE CASCADE
);

CREATE TABLE categoria(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    nome_inglese VARCHAR(50) NOT NULL
);

CREATE TABLE prodotto(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    nome_inglese VARCHAR(50) NOT NULL,
    ingredienti VARCHAR(255),
    tipo VARCHAR(50) NOT NULL,
    km0 BOOLEAN DEFAULT FALSE,
    vegano BOOLEAN DEFAULT FALSE,
    SlowFood BOOLEAN DEFAULT FALSE,
    bio BOOLEAN DEFAULT FALSE,
    innovativo BOOLEAN DEFAULT FALSE,
    ingredienti_visibili BOOLEAN DEFAULT FALSE,
    stato BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (tipo) REFERENCES categoria(nome) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabella per gli spot video
CREATE TABLE spot(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    percorso_video VARCHAR(255) NOT NULL,
    data_creazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    visibile BOOLEAN DEFAULT TRUE
);

-- Tabella per i prodotti del menu
CREATE TABLE menu(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    nome_inglese VARCHAR(50) NOT NULL,
    prezzo DECIMAL(10, 2) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    ingredienti_it VARCHAR(255),
    ingredienti_en VARCHAR(255),
    visibile BOOLEAN DEFAULT TRUE,
    extra VARCHAR(255),
    FOREIGN KEY (tipo) REFERENCES categoria(nome) ON DELETE RESTRICT ON UPDATE CASCADE
);