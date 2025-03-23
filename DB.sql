CREATE TABLE utente(
    username VARCHAR(20) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
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

-- Insert default categories
INSERT INTO categoria (nome, nome_inglese) VALUES
('gelato', 'ice cream'),
('granita', 'slush'),
('semifreddo', 'frozen dessert');

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