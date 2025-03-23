-- Create the new categoria table
CREATE TABLE IF NOT EXISTS categoria(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    nome_inglese VARCHAR(50) NOT NULL
);

-- Insert default categories based on current values
INSERT INTO categoria (nome, nome_inglese) VALUES
('gelato', 'ice cream'),
('granita', 'slush'),
('semifreddo', 'frozen dessert')
ON DUPLICATE KEY UPDATE nome_inglese = VALUES(nome_inglese);

-- Temporarily remove foreign key constraints (if any exist)
SET FOREIGN_KEY_CHECKS = 0;

-- Modify prodotto table to change tipo from ENUM to VARCHAR without losing data
ALTER TABLE prodotto MODIFY COLUMN tipo VARCHAR(50) NOT NULL;

-- Add foreign key constraint
ALTER TABLE prodotto
ADD CONSTRAINT fk_prodotto_categoria
FOREIGN KEY (tipo) REFERENCES categoria(nome)
ON DELETE RESTRICT ON UPDATE CASCADE;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
