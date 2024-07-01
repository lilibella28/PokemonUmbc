<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <?php
    include '../database/pdo.php';

    try {
       

        // $sql = "SELECT *, Name FROM pokemon_data";
        // $stmt = $conn->prepare($sql);
        // $stmt->execute();

        // echo "<ul>";
        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //     echo "<li>";
        //     echo "ID: " . ($row['ID']) . "<br>";
        //     echo "Name: " . ($row['Name']) . "<br>";
        //     echo "Type1: " . ($row['Type1']) . "<br>";
        //     echo "Type2: " . ($row['Type2']) . "<br>";
        //     echo "Total: " . ($row['Total']) . "<br>";
        //     echo "HP: " . ($row['Attack']) . "<br>";
        //     echo "Defense: " . ($row['Defense']) . "<br>";
        //     echo "SpAtk: " . ($row['SpAtk']) . "<br>";
        //     echo "SDef: " . ($row['SpDef']) . "<br>";
        //     echo "Generation: " . ($row['Generation']) . "<br>";
        //     echo "Legendary: " . ($row['Legendary']) . "<br>";
        //     echo "</li>";
        // }

        // echo "<h2>User Team</h2>";
        // $sql = "SELECT * FROM UserTeam";
        // $stmt = $conn->prepare($sql);
        // $stmt->execute();

        // echo "<ul>";
        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //     echo "<li>";
        //     echo "UserID: " . htmlspecialchars($row['UserID']) . "<br>";
        //     echo "PokemonSlot: " . htmlspecialchars($row['PokemonSlot']) . "<br>";
        //     echo "ID: " . htmlspecialchars($row['ID']) . "<br>";
        //     echo "Level: " . htmlspecialchars($row['Level']) . "<br>";
        //     echo "HP: " . htmlspecialchars($row['HP']) . "<br>";
        //     echo "Attack: " . htmlspecialchars($row['Attack']) . "<br>";
        //     echo "Defense: " . htmlspecialchars($row['Defense']) . "<br>";
        //     echo "SpecialAttack: " . htmlspecialchars($row['SpecialAttack']) . "<br>";
        //     echo "SpecialDefense: " . htmlspecialchars($row['SpecialDefense']) . "<br>";
        //     echo "Speed: " . htmlspecialchars($row['Speed']) . "<br>";
        //     echo "Move1: " . htmlspecialchars($row['Move1']) . "<br>";
        //     echo "Move2: " . htmlspecialchars($row['Move2']) . "<br>";
        //     echo "Move3: " . htmlspecialchars($row['Move3']) . "<br>";
        //     echo "Move4: " . htmlspecialchars($row['Move4']) . "<br>";
        //     echo "ExperiencePoints: " . htmlspecialchars($row['ExperiencePoints']) . "<br>";
        //     echo "</li>";
        // }
        // echo "</ul>";

        // echo "<h2>Wild Encounters</h2>";
        // $sql = "SELECT * FROM WildEncounters";
        // $stmt = $conn->prepare($sql);
        // $stmt->execute();

        // echo "<ul>";
        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //     echo "<li>";
        //     echo "EncounterID: " . htmlspecialchars($row['EncounterID']) . "<br>";
        //     echo "ID: " . htmlspecialchars($row['ID']) . "<br>";
        //     echo "Location: " . htmlspecialchars($row['Location']) . "<br>";
        //     echo "MinLevel: " . htmlspecialchars($row['MinLevel']) . "<br>";
        //     echo "MaxLevel: " . htmlspecialchars($row['MaxLevel']) . "<br>";
        //     echo "EncounterRate: " . htmlspecialchars($row['EncounterRate']) . "<br>";
        //     echo "</li>";
        // }
        // echo "</ul>";

       

        echo "<h2>Evolution Details</h2>";
        $sql = "SELECT * FROM Evolution";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>";
            echo "ID: " . htmlspecialchars($row['ID']) . "<br>";
            echo "EvolutionLevel: " . htmlspecialchars($row['EvolutionLevel']) . "<br>";
            echo "EvolutionMethod: " . htmlspecialchars($row['EvolutionMethod']) . "<br>";
            echo "EvolvedFormID: " . htmlspecialchars($row['EvolvedFormID']) . "<br>";
            echo "</li>";
        }
        echo "</ul>";
    } catch (PDOException $e) {
        echo "<p>connection failed: " . $e->getMessage() . "</p>";
        die();
    } catch (Exception $e) {
        echo "<p>error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>

</html>
