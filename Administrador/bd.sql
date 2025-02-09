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
    health INT NOT NULL,
    attack INT NOT NULL,
    defense INT NOT NULL,
    speed INT NOT NULL,
    type ENUM('Vacuna', 'Virus', 'Animal', 'Planta', 'Elemental') NOT NULL,
    level TINYINT NOT NULL,
    next_evolution_id INT NULL, -- ID del siguiente Digimon
    CONSTRAINT fk_next_evolution FOREIGN KEY (next_evolution_id) REFERENCES digimons(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE moves (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    description VARCHAR(255) NOT NULL,
    power INT NOT NULL,
    accuracy INT NOT NULL
);

CREATE TABLE digimons_moves (
    move_id INT NOT NULL ,
    digimon_id INT NOT NULL,
    PRIMARY KEY (move_id, digimon_id),
    CONSTRAINT fk_digimons_moves_digimons FOREIGN KEY (digimon_id) REFERENCES digimons(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_digimons_moves_moves FOREIGN KEY (move_id) REFERENCES moves(id) ON UPDATE CASCADE ON DELETE CASCADE
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

SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO users(username, password) VALUES("dani", "$2y$10$0cAboDTqx/BycOAYBupeOeGdXQdQHzjax6Hb5HM9rbYX6QX4mzif6");
INSERT INTO users(username, password) VALUES("jesus", "$10$iY8JAOtOZCb/i64joKyjBuGJ0lfDwdBWWyhS1wcanVT5v4dSn2gDW");

INSERT INTO digimons (name, health, attack, defense, speed, type, level, next_evolution_id) VALUES
-- Tipo Vacuna
('Koromon', 100, 50, 30, 40, 'Vacuna', 1, 2),
('Agumon', 150, 80, 50, 50, 'Vacuna', 2, 3),
('Greymon', 200, 120, 80, 60, 'Vacuna', 3, 4),
('MetalGreymon', 250, 160, 110, 70, 'Vacuna', 4, NULL),
('Gabumon', 140, 60, 40, 45, 'Vacuna', 1, 6),
('Garurumon', 150, 80, 60, 55, 'Vacuna', 2, 7),
('Garudamon', 190, 130, 90, 65, 'Vacuna', 3, 8),
('WereGarurumon', 240, 170, 120, 75, 'Vacuna', 4, NULL),
('Patamon', 120, 50, 40, 55, 'Vacuna', 1, 10),
('Angemon', 180, 100, 70, 65, 'Vacuna', 2, 11),
('MagnaAngemon', 230, 150, 100, 80, 'Vacuna', 3, NULL),

-- Tipo Virus
('Tsukaimon', 110, 45, 35, 40, 'Virus', 1, 13),
('Devimon', 160, 85, 55, 55, 'Virus', 2, 14),
('Myotismon', 210, 130, 90, 70, 'Virus', 3, 15),
('VenomMyotismon', 260, 170, 120, 85, 'Virus', 4, NULL),
('Impmon', 130, 60, 40, 50, 'Virus', 1, 17),
('Beelzebumon', 180, 110, 80, 65, 'Virus', 2, 18),
('Belphemon', 230, 160, 110, 75, 'Virus', 3, 19),
('Lucemon', 280, 190, 130, 90, 'Virus', 4, NULL),
('Dracmon', 100, 40, 30, 35, 'Virus', 1, 21),
('Baomon', 140, 60, 40, 50, 'Virus', 2, NULL),

-- Tipo Planta
('Tanemon', 100, 40, 35, 40, 'Planta', 1, 23),
('Palmon', 150, 75, 55, 55, 'Planta', 2, 24),
('Togemon', 200, 115, 85, 65, 'Planta', 3, 25),
('Lillymon', 250, 155, 115, 75, 'Planta', 4, NULL),
('Hagurumon', 110, 40, 35, 35, 'Planta', 1, 27),
('Lalamon', 140, 60, 50, 50, 'Planta', 2, 28),
('Lopmon', 190, 100, 75, 60, 'Planta', 3, 29),
('Turuiemon', 240, 150, 110, 70, 'Planta', 4, NULL),
('Salamon', 100, 35, 20, 20, 'Planta', 1, 31),
('Bokomon', 130, 70, 50, 45, 'Planta', 2, 32),
('Rosemon', 270, 180, 120, 85, 'Planta', 3, NULL),

-- Tipo Animal
('Elecmon', 120, 50, 40, 55, 'Animal', 1, 34),
('Leomon', 170, 90, 70, 60, 'Animal', 2, 35),
('GrapLeomon', 220, 130, 100, 70, 'Animal', 3, 36),
('SaberLeomon', 270, 180, 130, 85, 'Animal', 4, NULL),
('Nyaromon', 100, 45, 35, 50, 'Animal', 1, 38),
('Nefertimon', 150, 55, 20, 60, 'Animal', 2, 39),
('Diaboromon', 280, 180, 140, 90, 'Animal', 3, NULL),
('Tentomon', 120, 50, 40, 55, 'Animal', 1, 41),
('Kabuterimon', 170, 90, 70, 65, 'Animal', 2, 42),
('MegaKabuterimon', 220, 130, 100, 75, 'Animal', 3, 43),
('Wargreymon', 260, 160, 120, 80, 'Animal', 4, NULL),
('Falcomon', 130, 60, 45, 55, 'Animal', 1, 45),
('Hawkmon', 180, 100, 70, 65, 'Animal', 2, 46),
('Shurimon', 230, 140, 100, 75, 'Animal', 3, 47),
('ShiningGreymon', 270, 180, 130, 85, 'Animal', 4, NULL),

-- Tipo Elemental
('ElementalMon', 110, 50, 30, 40, 'Elemental', 1, 49),
('AguaMon', 160, 80, 50, 55, 'Elemental', 2, 50),
('FuegoMon', 210, 120, 80, 70, 'Elemental', 3, 51),
('MangoMon', 260, 160, 110, 85, 'Elemental', 4, NULL),
('MonMonMon', 140, 60, 40, 50, 'Elemental', 1, 53),
('RaMon', 220, 130, 90, 70, 'Elemental', 2, 54),
('CoMon', 270, 170, 120, 85, 'Elemental', 3, NULL),
('TierraMon', 120, 50, 40, 55, 'Elemental', 1, 56),
('HieloMon', 180, 100, 70, 65, 'Elemental', 2, 57),
('TestMon', 230, 150, 100, 75, 'Elemental', 3, 58),
('CopiaMon', 200, 69, 69, 69, 'Elemental', 4, NULL);

INSERT INTO moves (name, description, power, accuracy) VALUES
('Mega Punch', 'Un puñetazo devastador', 80, 7),
('Electro Shock', 'Descarga eléctrica paralizante', 60, 8),
('Fire Blast', 'Llamarada de fuego intensa', 100, 6),
('Aqua Jet', 'Chorro de agua veloz', 70, 9),
('Leaf Cutter', 'Cuchillas de hojas afiladas', 75, 7),
('Earthquake', 'Sacude la tierra con gran fuerza', 90, 5),
('Ice Beam', 'Rayo de hielo que congela al enemigo', 85, 6),
('Shadow Claw', 'Garra sombría con gran precisión', 65, 9),
('Wind Cutter', 'Cortes de viento afilados', 55, 10),
('Rock Smash', 'Golpe aplastante de roca', 95, 4);

INSERT INTO digimons_moves (move_id, digimon_id)
VALUES
(1, 1), (2, 1), (3, 1), (4, 1),
(2, 2), (3, 2), (4, 2), (5, 2),
(3, 3), (4, 3), (5, 3), (6, 3),
(1, 4), (2, 4), (3, 4), (7, 4),
(5, 5), (6, 5), (7, 5), (8, 5),
(6, 6), (7, 6), (8, 6), (9, 6),
(4, 7), (5, 7), (9, 7), (10, 7),
(1, 8), (6, 8), (9, 8), (10, 8),
(2, 9), (3, 9), (8, 9), (10, 9),
(1, 10), (2, 10), (4, 10), (5, 10),
(3, 11), (5, 11), (7, 11), (9, 11),
(1, 12), (6, 12), (8, 12), (10, 12),
(2, 13), (4, 13), (6, 13), (7, 13),
(3, 14), (5, 14), (7, 14), (9, 14),
(2, 15), (4, 15), (6, 15), (8, 15),
(1, 16), (3, 16), (5, 16), (10, 16),
(2, 17), (4, 17), (7, 17), (9, 17),
(1, 18), (5, 18), (6, 18), (8, 18),
(3, 19), (4, 19), (8, 19), (10, 19),
(2, 20), (5, 20), (6, 20), (7, 20),
(1, 21), (3, 21), (6, 21), (9, 21),
(4, 22), (5, 22), (7, 22), (10, 22),
(2, 23), (4, 23), (6, 23), (8, 23),
(3, 24), (5, 24), (7, 24), (9, 24),
(1, 25), (4, 25), (6, 25), (8, 25),
(2, 26), (5, 26), (9, 26), (10, 26),
(1, 27), (3, 27), (6, 27), (8, 27),
(4, 28), (5, 28), (7, 28), (9, 28),
(2, 29), (3, 29), (6, 29), (10, 29),
(1, 30), (4, 30), (7, 30), (9, 30),
(2, 31), (5, 31), (8, 31), (10, 31),
(1, 32), (3, 32), (6, 32), (9, 32),
(4, 33), (5, 33), (7, 33), (8, 33),
(2, 34), (3, 34), (6, 34), (10, 34),
(1, 35), (4, 35), (6, 35), (8, 35),
(3, 36), (5, 36), (9, 36), (10, 36),
(1, 37), (4, 37), (6, 37), (8, 37),
(2, 38), (3, 38), (7, 38), (9, 38),
(5, 39), (6, 39), (8, 39), (10, 39),
(1, 40), (3, 40), (7, 40), (9, 40),
(4, 41), (5, 41), (8, 41), (10, 41),
(2, 42), (3, 42), (6, 42), (9, 42),
(1, 43), (4, 43), (7, 43), (10, 43),
(5, 44), (6, 44), (8, 44), (9, 44),
(2, 45), (3, 45), (7, 45), (10, 45),
(1, 46), (4, 46), (6, 46), (8, 46),
(3, 47), (5, 47), (7, 47), (9, 47),
(1, 48), (4, 48), (6, 48), (9, 48),
(2, 49), (3, 49), (5, 49), (10, 49),
(1, 50), (4, 50), (7, 50), (9, 50),
(2, 51), (6, 51), (8, 51), (10, 51),
(1, 52), (5, 52), (6, 52), (7, 52),
(2, 53), (4, 53), (6, 53), (9, 53),
(1, 54), (3, 54), (5, 54), (8, 54),
(2, 55), (4, 55), (6, 55), (10, 55);

SET FOREIGN_KEY_CHECKS = 1;

select * from users;
update users set digievolutions = 10 where id = 1;

select * from team_users;