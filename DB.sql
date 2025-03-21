CREATE TABLE utente(
    username VARCHAR(20) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE prodotto(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    nome_inglese VARCHAR(50) NOT NULL,
    ingredienti VARCHAR(255),
    tipo ENUM('gelato', 'granita', 'semifreddo') NOT NULL,
    km0 BOOLEAN DEFAULT FALSE,
    vegano BOOLEAN DEFAULT FALSE,
    SlowFood BOOLEAN DEFAULT FALSE,
    bio BOOLEAN DEFAULT FALSE,
    innovativo BOOLEAN DEFAULT FALSE,
    ingredienti_visibili BOOLEAN DEFAULT FALSE,
    stato BOOLEAN DEFAULT TRUE
);