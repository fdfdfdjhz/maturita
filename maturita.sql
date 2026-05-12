-- Vytvoření databáze (pokud ještě neexistuje)
CREATE DATABASE IF NOT EXISTS deskovky CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci;
USE deskovky;

-- Tabulka VYDAVATELSTVÍ
CREATE TABLE VYDAVATELSTVI (
    IDV INT AUTO_INCREMENT PRIMARY KEY,
    nazev_vydavatelstvi VARCHAR(255) NOT NULL
);

-- Tabulka HRA
CREATE TABLE HRA (
    IDH INT AUTO_INCREMENT PRIMARY KEY,
    nazev_hry VARCHAR(255) NOT NULL,
    rok_vydani INT,
    cena DECIMAL(10,2),
    IDV INT,
    FOREIGN KEY (IDV) REFERENCES VYDAVATELSTVI(IDV) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Vložení 5 záznamů do VYDAVATELSTVÍ
INSERT INTO VYDAVATELSTVI (nazev_vydavatelstvi) VALUES 
('Mindok'), ('Albi'), ('REXhry'), ('Blackfire'), ('Czech Games Edition');

-- Vložení 15 záznamů do tabulky HRA (celkem 20 záznamů v DB)
INSERT INTO HRA (nazev_hry, rok_vydani, cena, IDV) VALUES 
('Carcassonne', 2001, 699.00, 1),
('Osadníci z Katanu', 1995, 899.00, 2),
('Krycí jména', 2015, 450.00, 5),
('Duna: Impérium', 2020, 1399.00, 3),
('Dixit', 2008, 750.00, 4),
('Výbušná koťátka', 2015, 399.00, 4),
('Karak', 2017, 650.00, 2),
('Na křídlech', 2019, 1299.00, 1),
('Ztracený ostrov Arnak', 2020, 1450.00, 5),
('Divukraj', 2018, 1599.00, 3),
('Dobble', 2009, 350.00, 4),
('Mars Teraformace', 2016, 1199.00, 1),
('Kvedlalové z Kvedlinburku', 2018, 950.00, 2),
('Vládcové podzemí', 2009, 1100.00, 5),
('Scythe', 2016, 1899.00, 2);