USE pokemon;

CREATE TABLE IF NOT EXISTS pokemon (
    PokemonID INT PRIMARY KEY,
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
    Legendary BOOLEAN,
    Image VARCHAR(255)
);
