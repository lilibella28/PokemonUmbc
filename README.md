### README.md

# Pokémon Battle Game

## Overview
The Pokémon Battle Game is a project aimed at developing a basic Pokémon game focusing on core gameplay mechanics such as battling, capturing, and training Pokémon. This README provides an outline of the project structure and detailed descriptions of tasks and their implementations.

## Project Structure

```plaintext
PokémonBattleGame/
│
├── database/
│   └── create_database.sql            # Script to create the Pokémon game database
│
├── public/
│   ├── css/
│   │   └── index.css                  # Stylesheet for the game
│   ├── images/
│   │   └── placeholder.txt            # Placeholder for images
│   └── js/
│       └── index.js                   # Main JavaScript file
│
└── src/
    ├── battle/
    │   └── index.js                   # Battle logic implementation in JavaScript
    ├── capture/
    │   └── index.js                   # Capture mechanics implementation in JavaScript
    ├── config.php                     # Configuration file for database connection
    ├── index.php                      # Main entry point for the game in PHP
    ├── leveling/
    │   └── index.js                   # Leveling and training implementation in JavaScript
    └── utils/
        └── index.js                   # Utility functions for various operations
```

## Tasks and Implementations

### Database and PHP Part

1. **Database Creation**
   - Create a database for the Pokémon game.
 

2. **Table for All Pokémon**
   - Define a table to store all Pokémon available in the game.
   - **Fields**: Pokémon ID, Name, Type, Base Stats (HP, Attack, Defense, etc.), Moves, Evolution Details, etc.


3. **Table for User's Team**
   - Define a table to store the user's team of Pokémon.
   - **Fields**: User ID, Pokémon Slot (1-6), Pokémon ID, Level, Current Stats, Moves, Experience Points, etc.


4. **Table for Wild Encounters**
   - Define a table to store wild Pokémon encounters.
   - **Fields**: Encounter ID, Pokémon ID, Location, Level Range, Encounter Rate, etc.


5. **Optional: Table for NPC Teams**
   - Define a table to store non-playable character (NPC) teams.
   - **Fields**: NPC ID, Pokémon Slot (1-6), Pokémon ID, Level, Current Stats, Moves, etc.


6. **Optional: Evolution Table**
   - Define a table to store evolution details for each Pokémon.
   - **Fields**: Pokémon ID, Evolution Level, Evolution Method, Evolved Form ID, etc.
  

### Game Logic

1. **Battle Implementation**
   - Implement turn-based battle logic between player's Pokémon and wild Pokémon/NPC teams.
   - **Features**: Move selection (base attack and special attack), damage calculation, optional status effects, turn order, etc.
   - File: `src/battle/index.js`

2. **Capture Implementation**
   - Implement capture mechanics for wild Pokémon.
   - **Features**: Capture rate calculation, Poké Balls, success/failure feedback, adding captured Pokémon to the user's team, limit of 6 Pokémon.
   - File: `src/capture/index.js`

3. **Leveling/Training**
   - Implement leveling and experience gain for Pokémon.
   - **Features**: Experience points calculation, leveling up, stat increase, and optional evolution triggers.
   - File: `src/leveling/index.js`

## Getting Started

### Prerequisites
- PHP 7.4+
- MariaDB

### Installation
1. Clone the repository:
   ```bash
   git clone git@github.com:lilibella28/PokemonUmbc.git
   cd PokemonUmbc
   ```

2. Set up the database:
   ```bash
   mysql -u root -p < database/create_database.sql
   ```



## Contributing

### Creating a Feature Branch
1. **Create a new branch**:
   ```bash
   git checkout -b feature/<feature-name>
   ```
   Replace `<feature-name>` with a descriptive name for your feature.

2. **Make your changes**:
   Edit, add, and test your code.

3. **Add changes to staging**:
   ```bash
   git add .
   ```
   or to add specific files:
   ```bash
   git add <file1> <file2>
   ```

4. **Commit your changes**:
   ```bash
   git commit -m "Add feature: <feature-description>"
   ```
   Replace `<feature-description>` with a short description of the changes.

### Pushing to PokémonUMBC Repository
1. **Push your branch to the remote repository**:
   ```bash
   git push origin feature/<feature-name>
   ```

2. **Create a pull request**:
   Go to the repository on GitHub, navigate to your branch, and create a pull request to merge your feature branch into the main branch.

