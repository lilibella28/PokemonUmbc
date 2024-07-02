<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pokemon";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    function getWildData($conn, $ID) {
        $sql = "SELECT ID, Name, HP, Attack, Defense, SpAtk, SpDef, Speed, Type1, Type2 FROM pokemon_data WHERE ID = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
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
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'];
        $ID = $_POST['ID'];
        $userId = $_POST['userId'];
        $level = $_POST['level'];
    
        if ($action == 'getWildData') {
            $wildData = getWildData($conn, $ID);
            echo json_encode($wildData);
        } elseif ($action == 'addToParty') {
            $message = addToParty($conn, $userId, $ID, $level);
            echo $message;
        }
    }
   

} catch (PDOException $e) {
    echo "<p>Connection failed: " . $e->getMessage() . "</p>";
    die();
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture Pokémon</title>
</head>
<body>
    <h1>Capture Pokémon</h1>
    <form id="capture-form">
        <label for="wild-pokemon-id">Wild Pokémon ID:</label>
        <input type="number" id="wild-pokemon-id" name="wild-pokemon-id" required>
        <label for="user-id">User ID:</label>
        <input type="number" id="user-id" name="user-id" required>
        <label for="pokemon-level">Level:</label>
        <input type="number" id="pokemon-level" name="pokemon-level" min="1" max="100" required>
        <button type="button" onclick="getWildData()">Get Wild Pokémon Data</button>
        <button type="submit">Add to Party</button>
    </form>
    <div id="wild-data"></div>
    <div id="response"></div>

    <script>
        async function getWildData() {
            const ID = document.getElementById('wild-pokemon-id').value;
            const response = await fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'getWildData',
                    ID: ID
                })
            });
            const wildData = await response.json();
            document.getElementById('wild-data').innerText = JSON.stringify(wildData, null, 2);
        }

        document.getElementById('capture-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            const form = event.target;
            const ID = form.elements['wild-pokemon-id'].value;
            const userId = form.elements['user-id'].value;
            const level = form.elements['pokemon-level'].value;
            
            const response = await fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'addToParty',
                    ID: ID,
                    userId: userId,
                    level: level
                })
            });
            const result = await response.text();
            document.getElementById('response').innerText = result;
        });
    </script>
</body>
</html>
