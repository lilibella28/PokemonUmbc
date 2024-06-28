<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokemon";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Name, ID, and HP from the database
    $sql = "SELECT ID, Name, HP FROM pokemon_data";
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
<title>Pokemon Capture Simulation</title>
</head>
    <body>
    <h1>Select a Pokemon</h1>
    <form id="pokemon-form">
        <select id="pokemon-select" name="pokemon">
            <option value="">-- Select a Pokemon --</option>
            <?php foreach ($pokemons as $pokemon): ?>
                <!-- the value will be used to help the program determine what pokemon it is -->
                <option value="<?= $pokemon['ID'] ?>" data-hp="<?= $pokemon['HP'] ?>" monName="<?= $pokemon['Name'] ?>">
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
            <option value="4">repeat ball</option>
            <option value="5">quick ball</option>
            </select>
        <button type="submit">Submit</button>
    </form>
    <p id="selected-Mon">we should have a pokemon here</p>
    <p id="selected-pokemon">we should have a pokemon ID here</p>
    <p id="selected-current-HP">we should have its current hp here</p>
    <p id="selected-pokeball">we should have a pokeball here</p>
    <p id="tries"></p>
    <p id="result">when all options are here, the results of a capture should be here</p>
    <p id="message"></p>
    <!-- used for testing purposes only -->
    <table id="PokemonParty">
    <tr>
        <th>Pokemon</th>
        <th>ID</th>
        <th>HP</th>
    </tr>   
    <tr>
        <td class="partyMonName1"></td>
        <td class="partyMonID1"></td>
        <td class="partyMonHP1"></td>
    </tr>
    <tr>
        <td class="partyMonName2"></td>
        <td class="partyMonID2"></td>
        <td class="partyMonHP2"></td>
    </tr>
    <tr>
        <td class="partyMonName3"></td>
        <td class="partyMonID3"></td>
        <td class="partyMonHP3"></td>
    </tr>
    <tr>
        <td class="partyMonName4"></td>
        <td class="partyMonID4"></td>
        <td class="partyMonHP4"></td>
    </tr>
    <tr>
        <td class="partyMonName5"></td>
        <td class="partyMonID5"></td>
        <td class="partyMonHP5"></td>
    </tr>
    <tr>
        <td class="partyMonName6"></td>
        <td class="partyMonID6"></td>
        <td class="partyMonHP6"></td>
    </tr>
    </table>

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
            "name" : "repeatball",
            "rateModifier" : 1,
        },
        {
            "id" : 5,
            "name" : "quickball",
            "rateModifier" : 1,
        }
    ]

    function calcCatchRate(currHP,maxHP,ball,wildMonData,turn){
        // calculation based off of data from bulbapedia
        if((ball.name === "quickball") && (turn === 1)){
            console.log("quick draw confirmed");
            ball.rateModifier = 4;
        } else if((ball.name === "repeatball") && isInParty(wildMonData)){
            console.log("repeat confirmed");
            ball.rateModifier = 4;
        }
        var numerator = (1 + ((maxHP * 3)-(currHP * 2)) * ball.rateModifier);
        var denominator = (maxHP * 3);
        return numerator / denominator;
    }

    // make sure the turn is in the function call in the future
    function catchMon(ball,pokemon,turn,currHP,maxHP){
        // temporary place holder until I figure out how to get pokemon from database
        let isCaught = false;
        console.log(ball.name ,", GO!");
        let catchRate =calcCatchRate(currHP,maxHP,ball,pokemon,turn);
        let capture = Math.random();
        let result = "";
            if (capture < catchRate) {
                result = "We caught him!!!";
                isCaught = true;
                pushWildMonDataToUserParty(pokemon);
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

    // the following functions are used for testing purposes until battle.js is made
    // keeps track of attempts to catch a pokemon including successes
    let attempts = 1;
    document.getElementById('tries').innerText = `turns: ${attempts}`;
    // made by chatGPT
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
            // get percentage of hp you want the pokemon to have
            const currHP = document.getElementById('currHP');
            const currHPSelected =currHP.options[currHP.selectedIndex];
            let currHPCalc = 0;
            // print curr hp
            if(currHPSelected.value){
                currHPCalc = Math.round((parseInt(currHPSelected.value)/100)*parseInt(pokemonHp));
                document.getElementById('selected-current-HP').innerText = `currHP: ${currHPCalc}`;
            }else{
                document.getElementById('selected-current-HP').innerText = "where's the damage?";
            }
            // get pokeball
            const pokeballOption =document.getElementById("Pokeball");
            const pokeballId = pokeballOption.options[pokeballOption.selectedIndex];
            const pokeball =getBallInfo(parseInt(pokeballId.value))
            // print selected ball
            if(pokeballId.value){
                document.getElementById('selected-pokeball').innerText = `Selected Pokeball: ${pokeball.name}`;
            }else{
                document.getElementById('selected-pokeball').innerText = "where's your ball?"
            }
    
            let isCaught = catchMon(pokeball,monSelectedOption,attempts,currHPCalc,pokemonHp);
            if(isCaught){
                document.getElementById('message').innerText = `congratulations!!!`
                attempts++;
                document.getElementById('tries').innerText = `turns: ${attempts}`;
            }else{
                document.getElementById('message').innerText = `failure!!!`
                attempts++;
                document.getElementById('tries').innerText = `turns: ${attempts}`;
            }
    });
    // user party
    const userParty = [];
    let partyTotal = userParty.length;
    // adds pokemon into the user's party
     function pushWildMonDataToUserParty(wildMonData) {
            let toReplace = false;
            // enterData(wildMonData,toReplace,partyTotal);
            let monName =document.querySelector("#PokemonParty .partyMonName"+((partyTotal+1).toString()))
            let monID =document.querySelector("#PokemonParty .partyMonID"+((partyTotal+1).toString()))
            let monHP =document.querySelector("#PokemonParty .partyMonHP"+((partyTotal+1).toString()))
            // monName.textContent = wildMonData.getAttribute('monName');
            // monID.textContent = wildMonData.value;
            // monHP.textContent= wildMonData.getAttribute('data-hp');
            if (userParty.length < 6) {
                userParty.push(wildMonData);
                monName =document.querySelector("#PokemonParty .partyMonName"+((partyTotal+1).toString()))
                monID =document.querySelector("#PokemonParty .partyMonID"+((partyTotal+1).toString()))
                monHP =document.querySelector("#PokemonParty .partyMonHP"+((partyTotal+1).toString()))
                monName.textContent = wildMonData.getAttribute('monName');
                monID.textContent = wildMonData.value;
                monHP.textContent= wildMonData.getAttribute('data-hp');
                partyTotal++;
            } else {
                const replace = confirm("Your party is full. Do you want to replace a Pokemon?");
                if (replace) {
                    const monToReplace = prompt("Enter the position (1-6) of the Pokemon to replace:");
                    if (monToReplace >= 1 && monToReplace <= 6) {
                        toReplace = true;
                        userParty[monToReplace - 1] = wildMonData;
                        // enterData(wildMonData,toReplace,(monToReplace));
                        monName =document.querySelector("#PokemonParty .partyMonName"+((monToReplace).toString()))
                        monID =document.querySelector("#PokemonParty .partyMonID"+((monToReplace).toString()))
                        monHP =document.querySelector("#PokemonParty .partyMonHP"+((monToReplace).toString()))
                        monName.textContent = wildMonData.getAttribute('monName');
                        monID.textContent = wildMonData.value;
                        monHP.textContent= wildMonData.getAttribute('data-hp');
                    }
                }
            }
        }
   
    function isInParty(wildMonData){
        isIn = false;
        let wildMonId = wildMonData.value;
        for(i = 0; i < userParty.length; i++){
            if(wildMonId === userParty[i].value){
                isIn = true;
            }
        }
        return isIn;
    }

</script>