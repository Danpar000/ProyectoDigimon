DROP DATABASE IF EXISTS Digi;

CREATE DATABASE Digi  CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE Digi;

-- Tabla de usuarios
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT, -- ID del usuario
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    wins INT DEFAULT 0,
    loses INT DEFAULT 0,
    digievolutions INT DEFAULT 0, -- Cantidad de Digievoluciones disponibles
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Digimons
CREATE TABLE digimons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    -- health INT NOT NULL,
    attack INT NOT NULL,
    defense INT NOT NULL,
    -- speed INT NOT NULL,
    type ENUM('Vacuna', 'Virus', 'Animal', 'Planta', 'Elemental') NOT NULL,
    level TINYINT NOT NULL,
    next_evolution_id INT NULL, -- ID del siguiente Digimon
    CONSTRAINT fk_next_evolution FOREIGN KEY (next_evolution_id) REFERENCES digimons(id) ON UPDATE CASCADE ON DELETE SET NULL
);

-- Relación entre usuarios y Digimons
CREATE TABLE digimons_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    digimon_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_digimons_users_users FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_digimons_users_digimons FOREIGN KEY (digimon_id) REFERENCES digimons(id) ON UPDATE CASCADE
);

-- Equipos de usuarios
CREATE TABLE team_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    digimon_id INT NOT NULL,
    CONSTRAINT fk_team_users_users FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_team_users_digimons FOREIGN KEY (digimon_id) REFERENCES digimons(id) ON UPDATE CASCADE
);

INSERT INTO users(username, password) VALUES("dani", "$2y$10$0cAboDTqx/BycOAYBupeOeGdXQdQHzjax6Hb5HM9rbYX6QX4mzif6");

INSERT INTO digimons (name, attack, defense, type, level, next_evolution_id) VALUES
-- Tipo Vacuna
('Koromon', 50, 30, 'Vacuna', 1, NULL),
('Agumon', 80, 50, 'Vacuna', 2, NULL),
('Greymon', 120, 80, 'Vacuna', 3, NULL),
('MetalGreymon', 160, 110, 'Vacuna', 4, NULL),
('Gabumon', 60, 40, 'Vacuna', 1, NULL),
('Garurumon', 130, 90, 'Vacuna', 3, NULL),
('WereGarurumon', 170, 120, 'Vacuna', 4, NULL),
('Patamon', 50, 40, 'Vacuna', 1, NULL),
('Angemon', 100, 70, 'Vacuna', 2, NULL),
('MagnaAngemon', 150, 100, 'Vacuna', 3, NULL),

-- Tipo Virus
('Tsukaimon', 45, 35, 'Virus', 1, NULL),
('Devimon', 85, 55, 'Virus', 2, NULL),
('Myotismon', 130, 90, 'Virus', 3, NULL),
('VenomMyotismon', 170, 120, 'Virus', 4, NULL),
('Impmon', 60, 40, 'Virus', 1, NULL),
('Beelzebumon', 110, 80, 'Virus', 2, NULL),
('Belphemon', 160, 110, 'Virus', 3, NULL),
('Lucemon', 190, 130, 'Virus', 4, NULL),
('Dracmon', 40, 30, 'Virus', 1, NULL),
('Baomon', 60, 40, 'Virus', 2, NULL),

-- Tipo Planta
('Tanemon', 40, 35, 'Planta', 1, NULL),
('Palmon', 75, 55, 'Planta', 2, NULL),
('Togemon', 115, 85, 'Planta', 3, NULL),
('Lillymon', 155, 115, 'Planta', 4, NULL),
('Hagurumon', 40, 35, 'Planta', 1, NULL),
('Lalamon', 60, 50, 'Planta', 2, NULL),
('Lopmon', 100, 75, 'Planta', 3, NULL),
('Turuiemon', 150, 110, 'Planta', 4, NULL),
('Bokomon', 70, 50, 'Planta', 2, NULL),
('Rosemon', 180, 120, 'Planta', 4, NULL),

-- Tipo Animal
('Elecmon', 50, 40, 'Animal', 1, NULL),
('Leomon', 90, 70, 'Animal', 2, NULL),
('GrapLeomon', 130, 100, 'Animal', 3, NULL),
('SaberLeomon', 180, 130, 'Animal', 4, NULL),
('Nyaromon', 45, 35, 'Animal', 1, NULL),
('Diaboromon', 180, 140, 'Animal', 4, NULL),
('Tentomon', 50, 40, 'Animal', 1, NULL),
('Kabuterimon', 90, 70, 'Animal', 2, NULL),
('MegaKabuterimon', 130, 100, 'Animal', 3, NULL),
('Wargreymon', 160, 120, 'Animal', 4, NULL),
('Falcomon', 60, 45, 'Animal', 1, NULL),
('Hawkmon', 100, 70, 'Animal', 2, NULL),
('Shurimon', 140, 100, 'Animal', 3, NULL),
('ShiningGreymon', 180, 130, 'Animal', 4, NULL),

-- Tipo Elemental
('ElementalMon', 50, 30, 'Elemental', 1, NULL),
('AguaMon', 80, 50, 'Elemental', 2, NULL),
('FuegoMon', 120, 80, 'Elemental', 3, NULL),
('MangoMon', 160, 110, 'Elemental', 4, NULL),
('MonMonMon', 60, 40, 'Elemental', 1, NULL),
('RaMon', 130, 90, 'Elemental', 3, NULL),
('CoMon', 170, 120, 'Elemental', 4, NULL),
('TierraMon', 50, 40, 'Elemental', 1, NULL),
('HieloMon', 100, 70, 'Elemental', 2, NULL),
('TestMon', 150, 100, 'Elemental', 3, NULL),
('CopiaMon', 69, 69, 'Elemental', 4, NULL);
