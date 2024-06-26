USE pokemon;

CREATE TABLE IF NOT EXISTS pokemon_data (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(50),
    Type1 VARCHAR(20),
    Type2 VARCHAR(20),
    Total INT,
    HP INT,
    Attack INT,
    Defense INT,
    SpAtk INT,
    SpDef INT,
    Speed INT,
    Generation INT,
    Legendary BOOLEAN
);


CREATE TABLE IF NOT EXISTS pokemon_image (
ID INT  PRIMARY KEY AUTO_INCREMENT,
Image_name VARCHAR(255),
Image_data LONGBLOB
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;