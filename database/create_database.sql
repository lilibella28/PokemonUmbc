CREATE DATABASE IF NOT EXISTS pokemon;
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
    Legendary BOOLEAN,
    Moves TEXT, 
    EvolutionDetails TEXT 
);

CREATE TABLE IF NOT EXISTS UserTeam (
    UserID INT NOT NULL,
    PokemonSlot INT NOT NULL CHECK (PokemonSlot BETWEEN 1 AND 6),
    ID INT ,
    Level INT NOT NULL,
    HP INT NOT NULL,
    Attack INT NOT NULL,
    Defense INT NOT NULL,
    SpecialAttack INT NOT NULL,
    SpecialDefense INT NOT NULL,
    Speed INT NOT NULL,
    Move1 VARCHAR(50),
    Move2 VARCHAR(50),
    ExperiencePoints INT NOT NULL,
    PRIMARY KEY (UserID, PokemonSlot),
    FOREIGN KEY (ID) REFERENCES pokemon_data(ID)
);

CREATE TABLE IF NOT EXISTS WildEncounters (
    EncounterID INT AUTO_INCREMENT PRIMARY KEY,
    ID INT NOT NULL,
    Location VARCHAR(100) NOT NULL,
    MinLevel INT NOT NULL,
    MaxLevel INT NOT NULL,
    EncounterRate DECIMAL(5, 2) NOT NULL CHECK (EncounterRate >= 0 AND EncounterRate <= 100),
    FOREIGN KEY (ID) REFERENCES pokemon_data(ID)
);

CREATE TABLE IF NOT EXISTS NPCTeams (
    NPCID INT NOT NULL,
    PokemonSlot INT NOT NULL CHECK (PokemonSlot BETWEEN 1 AND 6),
    ID INT NOT NULL,
    Level INT NOT NULL,
    HP INT NOT NULL,
    Attack INT NOT NULL,
    Defense INT NOT NULL,
    SpecialAttack INT NOT NULL,
    SpecialDefense INT NOT NULL,
    Speed INT NOT NULL,
    Move1 VARCHAR(50),
    Move2 VARCHAR(50),
    PRIMARY KEY (NPCID, PokemonSlot),
    FOREIGN KEY (ID) REFERENCES pokemon_data(ID)
);

CREATE TABLE IF NOT EXISTS Evolution (
    ID INT NOT NULL,
    EvolutionLevel INT,
    EvolutionMethod VARCHAR(50),
    EvolvedFormID INT NOT NULL,
    PRIMARY KEY (ID, EvolvedFormID),
    FOREIGN KEY (ID) REFERENCES pokemon_data(ID),
    FOREIGN KEY (EvolvedFormID) REFERENCES pokemon_data(ID)
);
