CREATE TABLE user (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50) NOT NULL,
    surname     VARCHAR(50) NOT NULL,
    birthday    VARCHAR(50) NOT NULL,
    city        VARCHAR(50) NOT NULL,
    email       VARCHAR(50) NOT NULL,
    username    VARCHAR(50) NOT NULL,
    password    VARCHAR(50) NOT NULL,
    roles       VARCHAR(50) NOT NULL
);

CREATE TABLE animal (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    details             TEXT,
    interesting_facts   TEXT,
    location_id         INT NOT NULL,
    name                VARCHAR(50),
    age                 INT NOT NULL,
    sex                 VARCHAR(50),
    birthday            DATETIME,
    arrival_date        DATETIME,
    curiosities         TEXT
);

CREATE TABLE species (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50),
    parent_id   INT,       # NULL for root species
    FOREIGN KEY (parent_id) REFERENCES species(id)
);

CREATE TABLE adoption (
    user_id     INT NOT NULL,
    animal_id   INT NOT NULL,
    PRIMARY KEY (user_id, animal_id),
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (animal_id) REFERENCES animal(id)
);

CREATE TABLE visit (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    animal_id   INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (animal_id) REFERENCES animal(id)
);
