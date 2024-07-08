<?php
include '../../database/pdo.php';

try {
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

