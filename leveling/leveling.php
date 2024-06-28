<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokemon";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Name, ID, and HP from the database
    $sql = "SELECT ID, Name, HP, Attack, Defense, SpAtk, SpDef, Speed FROM pokemon_data";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all rows
    $pokemons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "<p>Connection failed: " . $e->getMessage() . "</p>";
        die();
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
?>
<!-- testing purposes only -->
<html>
<head>
</head>
<title>Pokemon Level Up Simulator</title>
</head>
<body>
<h1>Select a Pokemon</h1>
    <form id="pokemon-form">
        <select id="pokemon-select" name="pokemon">
            <option value="">-- Select a Pokemon --</option>
            <?php foreach ($pokemons as $pokemon): ?>
                <!-- the value will be used to help the program determine what pokemon it is -->
                <option value="<?= $pokemon['ID'] ?>" data-hp="<?= $pokemon['HP'] ?>" monName="<?= $pokemon['Name'] ?>"
                data-attack="<?= $pokemon['Attack'] ?>" data-defense="<?= $pokemon['Defense'] ?>" data-spatk="<?= $pokemon['SpAtk'] ?>"
                data-spdef="<?= $pokemon['SpDef'] ?>" data-speed="<?= $pokemon['Speed'] ?>">
                    <?= htmlspecialchars($pokemon['Name']) ?> (HP: <?= $pokemon['HP'] ?>)
                    (Attack: <?= $pokemon['Attack'] ?>) (Defense: <?= $pokemon['Defense'] ?>)
                    (SpAtk: <?= $pokemon['SpAtk'] ?>) (SpDef: <?= $pokemon['SpDef'] ?>)
                    (Speed: <?= $pokemon['Speed'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <select id="pokemon level">
            <option value="">--Pick the pokemon's Level</option>
            <?php for ($x = 1; $x <= 100; $x++): ?>
                <option value="<?=$x?>">
                    <?= $x ?>
                </option>
                <?php endfor; ?>
        </select>
        <select id="enemy-select" name="enemy">
            <option value="">-- Select an opponent to get the exp from --</option>
            <?php foreach ($pokemons as $pokemon): ?>
                <!-- the value will be used to help the program determine what pokemon it is -->
                <option value="<?= $pokemon['ID'] ?>" data-hp="<?= $pokemon['HP'] ?>" monName="<?= $pokemon['Name'] ?>">
                    <?= htmlspecialchars($pokemon['Name']) ?> (HP: <?= $pokemon['HP'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <select id="enemy level">
            <option value="">--Pick the enemy pokemon's Level</option>
            <?php for ($x = 1; $x <= 100; $x++): ?>
                <option value="<?=$x?>">
                    <?= $x ?>
                </option>
                <?php endfor; ?>
        </select>
        <button type="submit">submit</button>
    </form>
    <p id="selected-Mon">we should have a pokemon here</p>
    <p id="selected-pokemon">we should have a pokemon ID here</p>
    <p id="selected level">we should have a level here</p>
    <p id="selected-enemyMon">we should have the opponent here</p>
    <p id="selected-enemyID">we should have the opponent's ID here</p>
    <p id="selected enemy level">we should have the opponent's level here</p>
    <p id="results">after entering info, we'll have a message here</p>
    <p id="levelAlert"></p>
</body>
</html>
<!-- ####### -->
<script>
    // tracks the exp the pokemon has
    let totalMonEXP = 0;
    let currLevel = 0;
    document.getElementById('pokemon-form').addEventListener('submit', function(event) {
            event.preventDefault();
            // Pokemon
            const monSelect = document.getElementById('pokemon-select');
            const monSelectedOption = monSelect.options[monSelect.selectedIndex];
            if (monSelectedOption.value) {
                // the value will be a string
                const pokemonId = monSelectedOption.value;
                // the value will be a string
                const pokemonHp = monSelectedOption.getAttribute('data-hp');
                document.getElementById('selected-pokemon').innerText = `Selected Pokemon ID: ${pokemonId}, HP: ${pokemonHp}`;
                document.getElementById('selected-Mon').innerText = `selected mon: ${monSelectedOption.getAttribute('monName')}`;
            } else {
                document.getElementById('selected-pokemon').innerText = 'No Pokemon selected';
            }
            // get pokemon
            const pokemonId = monSelectedOption.value;
            const pokemonHp = monSelectedOption.getAttribute('data-hp');
            const pokemonLevel = document.getElementById("pokemon level");
            const selectedLevel =pokemonLevel.options[pokemonLevel.selectedIndex];
            if(selectedLevel.value){
                document.getElementById('selected level').innerText = `Selected Pokemon Level: ${selectedLevel.value}`;
            }else{
                document.getElementById('selected level').innerText = "you forgot your pokemon's level";
            }
            // Enemy Pokemon
            const enemySelect = document.getElementById('enemy-select');
            const enemySelectedOption = enemySelect.options[enemySelect.selectedIndex];
            if (enemySelectedOption.value) {
                // the value will be a string
                const enemyId = enemySelectedOption.value;
                // the value will be a string
                const enemyHp = enemySelectedOption.getAttribute('data-hp');
                document.getElementById('selected-enemyID').innerText = `Selected enemy ID: ${enemyId}, HP: ${enemyHp}`;
                document.getElementById('selected-enemyMon').innerText = `selected enemy: ${enemySelectedOption.getAttribute('monName')}`;
            } else {
                document.getElementById('selected-enemyID').innerText = 'No enemy selected';
            }
            // get enemy pokemon
            const enemyId = enemySelectedOption.value;
            const enemyHp = enemySelectedOption.getAttribute('data-hp');
            const enemyLevel = document.getElementById("enemy level");
            const selectedEnemyLevel =enemyLevel.options[enemyLevel.selectedIndex];
            if(enemyLevel.value){
                document.getElementById('selected enemy level').innerText = `Selected enemy Level: ${enemyLevel.value}`;
            }else{
                document.getElementById('selected enemy level').innerText = "you forgot your enemy's level";
            }
            currLevel = parseInt(selectedLevel.value);
            // stats
            const monAttack = parseInt(monSelectedOption.getAttribute('data-attack'));
            const monDefense = parseInt(monSelectedOption.getAttribute('data-defense'));
            const monSpAtk = parseInt(monSelectedOption.getAttribute('data-spatk'));
            const monSpDef = parseInt(monSelectedOption.getAttribute('data-spdef'));
            const monSpeed = parseInt(monSelectedOption.getAttribute('data-speed'));
            const baseMonStats = [
                {"stat" : "hp", "num" : parseInt(pokemonHp)},
                {"stat" : "attack", "num" : monAttack},
                {"stat" : "defense", "num" : monDefense},
                {"stat" : "spAtk", "num" : monSpAtk},
                {"stat" : "spDef", "num" : monSpDef},
                {"stat" : "speed", "num" : monSpeed}
            ]
            let monStats = [
                {"stat" : "hp", "num" : parseInt(pokemonHp)},
                {"stat" : "attack", "num" : monAttack},
                {"stat" : "defense", "num" : monDefense},
                {"stat" : "spAtk", "num" : monSpAtk},
                {"stat" : "spDef", "num" : monSpDef},
                {"stat" : "speed", "num" : monSpeed}
            ]
            //************/
            defeatedPokemonBaseExp = 64; // Example base experience for a defeated Pokémon
            var modifiers = new Array(1.5,1.2) // Example modifiers (e.g., Lucky Egg = 1.5, traded Pokémon = 1.2)
            var exp = calculateEXP(parseInt(selectedLevel.value),defeatedPokemonBaseExp,parseInt(enemyLevel.value),modifiers);
            totalMonEXP += exp;
            document.getElementById('results').innerText = `${monSelectedOption.getAttribute('monName')} has gained ${exp} exp`;
            checkLevelUp(monSelectedOption,totalMonEXP,monStats,baseMonStats);
    });

    // generic exp threshold
    let expTable = [
        { "level" : 1,
            "expReq" : 0
        },
        {
            "level" : 2,
            "expReq" : 100
        },
    ]

    for (i = 2; i < 98; i++){
        var expObj = {"level" : i+2,"expReq" : ((i+2)*100)};
        expTable.push(expObj);
    }
    let exp99 = {"level" : 99,"expReq" : 950000};
    expTable.push(exp99);
    let exp100 = {"level" : 100,"expReq" : 1000000};
    expTable.push(exp100);

    function calculateEXP(monLevel,deadMonBaseEXP,deadMonLevel,arr_modifiers){
        // Base formula for experience points
        exp = (deadMonBaseEXP * deadMonLevel) / 7;
        // Apply any modifiers (e.g., Lucky Egg, traded Pokémon, etc.)
        for(i = 0; i <arr_modifiers.length; i++){
            exp *= arr_modifiers[i];
        }
        return Math.round(exp);
    }

    function checkLevelUp(monSelectedOption,expGained,monStats,baseMonStats){
        while(currLevel <= 100 && expGained >= expTable[currLevel+1].expReq){
            currLevel++;
            document.getElementById("levelAlert").innerText = `congratualtions, 
            your ${monSelectedOption.getAttribute('monName')} grew to level ${currLevel}`
            updateStats(monStats,baseMonStats);
        }

    }

    function updateStats(monStats,baseMonStats){
        for(i = 0; i < monStats.length; i++){
            console.log(currLevel);
            console.log(monStats[i].stat," before level up: ", monStats[i].num);
            if(monStats[i].stat === "hp"){
                monStats[i].num = calcStat(baseMonStats[i].num,true);
            }else{
                monStats[i].num = calcStat(baseMonStats[i].num,false);
            }
            console.log(monStats[i].stat," after level up: ", monStats[i].num);
        }
    }

    function calcStat(baseStat,isHP){
        // I multiplying (2*baseStat)*currLevel) by 3 just so the stats can increase
        // without the use of ev and iv data
        stat = (((2*baseStat)*currLevel))/10;
        if(isHP){
            return Math.floor(stat+currLevel+10);
        }else{
            return Math.floor(stat+5);
        }
    }



</script>