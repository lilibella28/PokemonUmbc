<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokemon";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Name, ID, and HP from the database
    $sql = "SELECT ID, Name, Type1, Type2, HP, Speed FROM pokemon_data";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all rows
    $pokemons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // fetches wild encounter
    $sql = "SELECT * FROM WildEncounters";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    // gets wild pokemon's data
    function getWildData($conn, $ID) {
        $sql = "SELECT ID, Name, HP, Attack, Defense, SpAtk, SpDef, Speed, Type1, Type2 FROM pokemon_data WHERE ID = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getMonName($conn, $ID) {
        $sql = "SELECT * FROM pokemon_data WHERE ID = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":ID", $ID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['Name'];
    }
    // to be removed
    function checkParty($conn, $userId,$ID) {
        $isPresent = false;
        $sql = "SELECT ID FROM UserTeam WHERE UserID = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if($row['ID'] == $ID){
                $isPresent = true;
            }
        }
        return $isPresent;
    }
    // to be removed
    function getParty($conn, $userId){
        $sql = "SELECT * FROM UserTeam WHERE UserID = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // adds pokemon to user's party
    function addToParty($conn, $userId, $ID, $level) {
        // Check for an empty slot
        $sql = "SELECT MAX(PokemonSlot) AS max_slot FROM UserTeam WHERE UserID = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextSlot = $result['max_slot'] + 1;
    
        if ($nextSlot <= 6) {
            // Add to party
            $sql = "INSERT INTO UserTeam (UserID, PokemonSlot, ID, Level, HP, Attack, Defense, SpecialAttack, SpecialDefense, Speed, ExperiencePoints)
                    VALUES (:userId, :pokemonSlot, :ID, :level, :hp, :attack, :defense, :specialAttack, :specialDefense, :speed, 0)";
            $stmt = $conn->prepare($sql);
    
            $wildData = getWildData($conn, $ID);
    
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':pokemonSlot', $nextSlot);
            $stmt->bindParam(':ID', $ID);
            $stmt->bindParam(':level', $level);
            $stmt->bindParam(':hp', $wildData['HP']);
            $stmt->bindParam(':attack', $wildData['Attack']);
            $stmt->bindParam(':defense', $wildData['Defense']);
            $stmt->bindParam(':specialAttack', $wildData['SpAtk']);
            $stmt->bindParam(':specialDefense', $wildData['SpDef']);
            $stmt->bindParam(':speed', $wildData['Speed']);
            $stmt->execute();
            return "Pokémon added successfully!";
        } else {
            return "User's party is full!";
        }
    }

    // gets pokemon data from javascript
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
        $ID = $_POST['ID'];
        $userId = $_POST['userId'];
        $level = $_POST['level'];

        if($action == 'getParty'){
            $userParty = getParty($conn, $userId);
            echo json_encode($userParty);
        }else if($action == 'checkParty'){
            $isThere = checkParty($conn, $userId,$ID);
            echo json_encode($isThere);
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
<title>Pokemon Capture Simulation</title>
</head>
    <body>
    <h1>Select a Pokemon</h1>
    <form id="pokemon-form">
        <select id="pokemon-select" name="pokemon">
            <option value="">-- Select a Pokemon --</option>
            <?php foreach ($pokemons as $pokemon): ?>
                <!-- the value will be used to help the program determine what pokemon it is -->
                <option value="<?= $pokemon['ID'] ?>" data-hp="<?= $pokemon['HP'] ?>" monName="<?= $pokemon['Name'] ?>"
                monSpeed="<?= $pokemon['Speed'] ?>"  monType1="<?= $pokemon['Type1'] ?>" monType2="<?= $pokemon['Type2'] ?>">
                    <?= htmlspecialchars($pokemon['Name']) ?> (HP: <?= $pokemon['HP'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

            <select id="currHP">
            <option value="">--current health--</option>
            <option  value="100">100%</option>
            <option  value="75">75%</option>
            <option  value="50">50%</option>
            <option  value="25">25%</option>
            </select>

            <select id="Pokeball">
            <option>--pokeball--</option>
            <option value="1">poke ball</option>
            <option value="2">great ball</option>
            <option value="3">ultra ball</option>
            <option value="4">quick ball</option>
            <option value="5">net ball</option>
            <option value="6">fast ball</option>
            </select>

            <select id="pokemon level">
            <option value="">--Pick the pokemon's Level</option>
            <?php for ($x = 1; $x <= 100; $x++): ?>
                <option value="<?=$x?>">
                    <?= $x ?>
                </option>
                <?php endfor; ?>
        </select>

        <button type="submit">Submit</button>
    </form>
    <p id="selected-Mon">we should have a pokemon here</p>
    <p id="selected-pokemon">we should have a pokemon ID here</p>
    <p id="selected-current-HP">we should have its current hp here</p>
    <p id="selected-pokeball">we should have a pokeball here</p>
    <p id="selected-level">we should have a level here</p>
    <p id="tries"></p>
    <p id="wild-data"></p>
    <p id="result">when all options are here, the results of a capture should be here</p>
    <p id="message"></p>
    <p id="response"></p>
    <!-- used for testing purposes only -->
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
<!-- ###################### -->

<script>

        // used to recognize which pokeballs are in the game
        // and see what pokeball the user uses.
        let pokeballs = [
        {
            "id" : 1,
            "name" : "pokeball",
            "rateModifier" : 1,
        },
        {
            "id" : 2,
            "name" : "greatball",
            "rateModifier" : 1.5,
        },
        {
            "id" : 3,
            "name" : "ultraball",
            "rateModifier" : 2,
        },
        {
            "id" : 4,
            "name" : "quickball",
            "rateModifier" : 1,
        },
        {
            "id" : 5,
            "name" : "netball",
            "rateModifier" : 1,
        },
        {"id" : 6,
            "name" : "fastball",
            "rateModifier" : 1,
        }
    ]

    // temporary variable to test user ID:
    const myUserID = 1;   

    // checks if any of the pokemon's types match the passed in type
    function checkType(type,pokemon){
        if(pokemon.getAttribute('monType1') === type 
        || pokemon.getAttribute('monType2') === type){
            return true;
        }else{
            return false;
        }
    }

    function calcCatchRate(currHP,maxHP,ball,wildMonData,turn){
        // calculation based off of data from bulbapedia
        if((ball.name === "quickball") && (turn === 1)){
            console.log("quick draw confirmed");
            ball.rateModifier = 4;
        } else if((ball.name === "netball") && (checkType("Water",wildMonData) 
        || checkType("Bug",wildMonData))){
            console.log("net confirmed");
            ball.rateModifier = 3.5;
        } else if((ball.name === "fastball") && (parseInt(wildMonData.getAttribute('monSpeed')) >= 100 )){
            console.log("fast confirmed");
            ball.rateModifier = 4;
        } 
        var numerator = (1 + ((maxHP * 3)-(currHP * 2)) * ball.rateModifier);
        var denominator = (maxHP * 3);
        return numerator / denominator;
    }

    // make sure the turn is in the function call in the future
    function catchMon(ball,pokemon,turn,currHP,maxHP,level){
        // temporary place holder until I figure out how to get pokemon from database
        let isCaught = false;
        console.log(ball.name ,", GO!");
        let catchRate =calcCatchRate(currHP,maxHP,ball,pokemon,turn);
        let capture = Math.random();
        let result = "";
            if (capture < catchRate) {
                result = "We caught him!!!";
                isCaught = true;
                addToUser(pokemon,level)
                // pushWildMonDataToUserParty(pokemon);
            } else if (capture < catchRate * 0.2) {
                result = "Aww so close";
                isCaught = false
            } else {
                result = "The pokemon broke free!!!";
                isCaught = false
            }
        document.getElementById('result').innerText = result;
        return isCaught;
    }

    function getBallInfo(ballId) {
            console.log(pokeballs.find(ball => ball.id === ballId));
            return pokeballs.find(ball => ball.id === ballId);
    } 

    async function getWildData(pokemon) {
            const monID = pokemon.value;
            const response = await fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'getWildData',
                    ID: monID
                })
            });
            const wildData = await response.json();
            document.getElementById('wild-data').innerText = JSON.stringify(wildData, null, 2);
    }

    async function addToUser(pokemon,level){
        const monID = pokemon.value;
        const userId = myUserID;
        console.log("uploading to user party");
        const response = await fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'addToParty',
                    ID: monID,
                    userId: userId,
                    level: level
                })
        });
        const result = await response.text();
        document.getElementById('response').innerText = result;
    }

    // the following functions are used for testing purposes until battle.js is made
    // keeps track of attempts to catch a pokemon including successes
    let attempts = 1;
    document.getElementById('tries').innerText = `turns: ${attempts}`;
    // made by chatGPT
    document.getElementById('pokemon-form').addEventListener('submit', function(event) {
            event.preventDefault();
            // party = getPartyData(myUserID);
            // logParty(party);
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
                // get percentage of hp you want the pokemon to have
                const currHP = document.getElementById('currHP');
                const currHPSelected =currHP.options[currHP.selectedIndex];
                let currHPCalc = 0;
                // print curr hp
                if(currHPSelected.value){
                    currHPCalc = Math.round((parseInt(currHPSelected.value)/100)*parseInt(pokemonHp));
                    document.getElementById('selected-current-HP').innerText = `currHP: ${currHPCalc}`;
                    // get pokeball
                    const pokeballOption =document.getElementById("Pokeball");
                    const pokeballId = pokeballOption.options[pokeballOption.selectedIndex];
                    const pokeball =getBallInfo(parseInt(pokeballId.value))
                    // print selected ball
                    if(pokeballId.value){
                        document.getElementById('selected-pokeball').innerText = `Selected Pokeball: ${pokeball.name}`;
                        monLevel = document.getElementById('pokemon level');
                        monLevelSelect = monLevel.options[monLevel.selectedIndex];
                        if(monLevelSelect.value){
                            selectedLevel = parseInt(monLevelSelect.value);
                            document.getElementById('selected-level').innerText = `pokemon's level: ${selectedLevel}`
                            // getWildData(monSelectedOption);
                            let isCaught = catchMon(pokeball,monSelectedOption,attempts,currHPCalc,pokemonHp,selectedLevel);
                            if(isCaught){
                                document.getElementById('message').innerText = `congratulations!!!`
                                attempts++;
                                document.getElementById('tries').innerText = `turns: ${attempts}`;
                            }else{
                                document.getElementById('message').innerText = `failure!!!`
                                attempts++;
                                document.getElementById('tries').innerText = `turns: ${attempts}`;
                            }
                        }else{
                            document.getElementById('selected-level').innerText = "where's the pokemon's level?";
                        }
                    }else{
                        document.getElementById('selected-pokeball').innerText = "where's your ball?"
                    }
                }else{
                    document.getElementById('selected-current-HP').innerText = "where's the damage?";
                }
            } else {
                document.getElementById('selected-pokemon').innerText = 'No Pokemon selected';
            }
    });

</script>