CREATE TABLE utente(
    username VARCHAR(20) PRIMARY KEY,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE prodotto(
    nome VARCHAR(50) NOT NULL PRIMARY KEY,
    nome_inglese VARCHAR(50) NOT NULL,
    ingredienti VARCHAR(255),
    tag VARCHAR(255), -- percorso di una o più immagini
    tipo ENUM('gelato', 'granita', 'semifreddo') NOT NULL,
    km0 BOOLEAN DEFAULT FALSE,
    vegano BOOLEAN DEFAULT FALSE,
    SlowFood BOOLEAN DEFAULT FALSE,
    bio BOOLEAN DEFAULT FALSE,
    innovativo BOOLEAN DEFAULT FALSE,
    ingredienti_visibili BOOLEAN DEFAULT FALSE,
    stato BOOLEAN DEFAULT TRUE
);

-- Modificato per supportare più dispositivi per utente con campi opzionali
DROP TABLE IF EXISTS utente_remember;
CREATE TABLE utente_remember (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    token VARCHAR(191) NOT NULL UNIQUE,  -- Reduced from 255 to 191
    user_agent VARCHAR(255) NULL,        -- Campo reso esplicitamente opzionale
    ip_address VARCHAR(45) NULL,         -- Campo reso esplicitamente opzionale
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (username) REFERENCES utente(username) ON DELETE CASCADE
);
