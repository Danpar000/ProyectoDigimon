CREATE DATABASE Digi  CHARACTER SET utf8 COLLATE utf8_spanish_ci;;
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
    image VARCHAR(255),
    CONSTRAINT fk_next_evolution FOREIGN KEY (next_evolution_id) REFERENCES digimons(id) ON UPDATE CASCADE
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

