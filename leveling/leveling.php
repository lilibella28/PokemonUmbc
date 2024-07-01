<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokemon";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Name, ID, and HP from the database
    $sql = "SELECT * FROM pokemon_data";
    $stmt = $conn->prepare($sql);
    $stmt->execute();


    // Fetch all rows
    $pokemons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // gets user party
    $sql = "SELECT * FROM UserTeam";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $stmt = $conn->query("SELECT * FROM UserTeam");
    $partyMembers = $stmt->fetchALL(PDO::FETCH_ASSOC);

    function updateLevel($conn, $userId,$ID,$level){
        $sql = "UPDATE UserTeam SET Level=($level++) WHERE UserID = :userId AND ID = :ID AND Level = :level";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":UserID", $userId);
        $stmt->bindValue(":ID", $ID);
        $stmt->bindValue(":Level", $level);
        $stmt->execute();
        return "level up!";
    }

    function updateStat($conn, $userId,$ID,$stat,$level){
        $sql = "UPDATE UserTeam SET {$stat['stat']} = :value WHERE UserID = :userId AND ID = :ID AND Level = :level";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':value', $stat['num']);
        $stmt->bindValue(":UserID", $userId);
        $stmt->bindValue(":ID", $ID);
        $stmt->bindValue(":Level", $level);
        $stmt->execute();
        return "level up!";
    }

    function getMonName($conn, $ID) {
        $sql = "SELECT * FROM pokemon_data WHERE ID = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":ID", $ID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['Name'];
    }

    // gets evolution ID for pokemon
    $sql = "SELECT * FROM Evolution";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // gets pokemon data from javascript
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
        $ID = $_POST['ID'];
        $userId = $_POST['userId'];
        $level = $_POST['pokemonLevel'];
        $stat = $_POST['monStats[i].num'];

        if($action == 'updateLevel'){
            $message = updateLevel($conn, $userId,$ID,$level);
            echo $message;
        }else if($action == 'updateStat'){
            $message = updateStat($conn, $userId,$ID,$stat,$level);
            echo $message;
        }else if ($action == 'getWildData') {
            $wildData = getWildData($conn, $ID);
            echo json_encode($wildData);
        } elseif ($action == 'addToParty') {
            $message = addToParty($conn, $userId, $ID, $level);
            echo $message;
        }
        exit;
    }

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
            <option value="">-- Select a Pokemon from your party --</option>
            <?php foreach ($partyMembers as $member): ?>
                <!-- the value will be used to help the program determine what pokemon it is -->
                <option value="<?= $member['ID'] ?>" monName="<?php getMonName($conn,$member['ID']) ?>" 
                monLevel="<?= $member['Level'] ?>" data-hp="<?= $member['HP'] ?>" data-attack="<?= $member['Attack'] ?>" data-defense="<?= $member['Defense'] ?>" 
                data-spatk="<?= $member['SpecialAttack'] ?>" data-spdef="<?= $member['SpecialDefense'] ?>" data-speed="<?= $member['Speed'] ?>"
                exp="<?= $member['ExperiencePoints'] ?>" userID="<?= $member['UserID'] ?>">
                    <?= htmlspecialchars(getMonName($conn,$member['ID']) ) ?> (ID: <?= $member['ID'] ?>)
                    (Level: <?= $member['Level'] ?>)
                </option>
            <?php endforeach; ?>
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
    <p id="response"></p>
    <p id="message"></p>
    <p id="levelAlert"></p>
    <?php 
        // displays user's party
        $stmt = $conn->query("SELECT * FROM UserTeam");
        echo "<table border='1'>";
        echo "<tr><th> userID </th><th>Name</th><th>Level</th><th>ID</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['UserID'] . "</td>";
            echo "<td>" . getMonName($conn,$row['ID']) . "</td>";
            echo "<td>" . $row['Level'] . "</td>";
            echo "<td>" . $row['ID'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    
    ?>
</body>
</html>
<!-- ####### -->
<script>
    document.getElementById('pokemon-form').addEventListener('submit', function(event) {
            event.preventDefault();
            // Pokemon
            const monSelect = document.getElementById('pokemon-select');
            const monSelectedOption = monSelect.options[monSelect.selectedIndex];
            if (monSelectedOption.value) {
                // the value will be a string
                const pokemonId = monSelectedOption.value;
                const pokemonName = monSelectedOption.getAttribute('monName');
                let pokemonLevel = parseInt(monSelectedOption.getAttribute('monLevel'));
                let pokemonExp = monSelectedOption.getAttribute('exp');
                document.getElementById('selected-pokemon').innerText = `Selected Pokemon ID: ${pokemonId}`;
                document.getElementById('selected-Mon').innerText = `selected mon: ${pokemonName}`;
                document.getElementById('selected level').innerText = `Pokemon Level: ${pokemonLevel}`;
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
                    // get enemy pokemon
                    const enemyLevel = document.getElementById("enemy level");
                    const selectedEnemyLevel =enemyLevel.options[enemyLevel.selectedIndex];
                    if(enemyLevel.value){
                        document.getElementById('selected enemy level').innerText = `Selected enemy Level: ${enemyLevel.value}`;
                        // stats
                        let pokemonHp = parseInt(monSelectedOption.getAttribute('data-hp'));
                        let monAttack = parseInt(monSelectedOption.getAttribute('data-attack'));
                        let monDefense = parseInt(monSelectedOption.getAttribute('data-defense'));
                        let monSpAtk = parseInt(monSelectedOption.getAttribute('data-spatk'));
                        let monSpDef = parseInt(monSelectedOption.getAttribute('data-spdef'));
                        let monSpeed = parseInt(monSelectedOption.getAttribute('data-speed'));
                        // used to calculate how many points each stat should increase
                        const baseMonStats = [
                            {"stat" : "HP", "num" : pokemonHp},
                            {"stat" : "Attack", "num" : monAttack},
                            {"stat" : "Defense", "num" : monDefense},
                            {"stat" : "SpecialAttack", "num" : monSpAtk},
                            {"stat" : "SpecialDefense", "num" : monSpDef},
                            {"stat" : "Speed", "num" : monSpeed}
                        ]
                        // changes as the pokemon levels up
                        let monStats = [
                            {"stat" : "HP", "num" : pokemonHp},
                            {"stat" : "Attack", "num" : monAttack},
                            {"stat" : "Defense", "num" : monDefense},
                            {"stat" : "SpecialAttack", "num" : monSpAtk},
                            {"stat" : "SpecialDefense", "num" : monSpDef},
                            {"stat" : "Speed", "num" : monSpeed}
                        ]
                        //************/
                        defeatedPokemonBaseExp = 64; // Example base experience for a defeated Pokémon
                        var modifiers = new Array(1.5,1.2) // Example modifiers (e.g., Lucky Egg = 1.5, traded Pokémon = 1.2)
                        var exp = calculateEXP(pokemonLevel,defeatedPokemonBaseExp,parseInt(enemyLevel.value),modifiers);
                        pokemonExp += exp;
                        updateExp(pokemonExp);
                        document.getElementById('results').innerText = `${pokemonName} has gained ${exp} exp`;
                        checkLevelUp(monSelectedOption,pokemonLevel,pokemonExp,monStats,baseMonStats);
                    }else{
                        document.getElementById('selected enemy level').innerText = "you forgot your enemy's level";
                    }
                } else {
                    document.getElementById('selected-enemyID').innerText = 'No enemy selected';
                }
            } else {
                document.getElementById('selected-pokemon').innerText = 'No Pokemon selected';
            }
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

    async function updateExp(pokemonExp){

    }

    function calculateEXP(monLevel,deadMonBaseEXP,deadMonLevel,arr_modifiers){
        // Base formula for experience points
        exp = (deadMonBaseEXP * deadMonLevel) / 7;
        // Apply any modifiers (e.g., Lucky Egg, traded Pokémon, etc.)
        for(i = 0; i <arr_modifiers.length; i++){
            exp *= arr_modifiers[i];
        }
        return Math.round(exp);
    }

    async function updateLevel(monSelectedOption,pokemonLevel){
        console.log("updating level");
        const response = await fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'updateLevel',
                    ID: monSelectedOption.value,
                    userId: monSelectedOption.getAttribute('userID'),
                    level: pokemonLevel
                })
        });
        const result = await response.text();
        document.getElementById('response').innerText = result;
    }
    
    function checkLevelUp(monSelectedOption,pokemonLevel,expGained,monStats,baseMonStats){
        console.log("level before check: ", pokemonLevel);
        console.log("currEXP ammount: ", expGained);
        while(pokemonLevel <= 100 && expGained >= expTable[pokemonLevel+1].expReq){
            console.log("boosting level");
            updateLevel(monSelectedOption,pokemonLevel);
            document.getElementById("levelAlert").innerText = `congratualtions, 
            your ${monSelectedOption.getAttribute('monName')} grew to level ${pokemonLevel}`
            updateStats(monSelectedOption,monStats,baseMonStats);
        }
        console.log("level after check: ", pokemonLevel);
    }

    async function transferStat(stat){
        // calls php function updateStat
        const response = await fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                action: 'updateHP',
                ID: monSelectedOption.value,
                userId: monSelectedOption.getAttribute('userID'),
                level: pokemonLevel,
                Stat: stat
            })
        });
    }

    function updateStats(monSelectedOption,monStats,baseMonStats){
        currLevel = monSelectedOption.getAttribute('monLevel');
        for(i = 0; i < monStats.length; i++){
            console.log(currLevel);
            console.log(monStats[i].stat," before level up: ", monStats[i].num);
            if(monStats[i].stat === "HP"){
                monStats[i].num = calcStat(baseMonStats[i].num,true,currLevel);
                transferStat(monStats[i]);
            }else{
                monStats[i].num = calcStat(baseMonStats[i].num,false,currLevel);
                transferStat(monStats[i]);
            }
            console.log(monStats[i].stat," after level up: ", monStats[i].num);
        }
    }

    function calcStat(baseStat,isHP,currLevel){
        stat = (((2*baseStat)*currLevel))/10;
        if(isHP){
            return Math.floor(stat+currLevel+10);
        }else{
            return Math.floor(stat+5);
        }
    }



</script>