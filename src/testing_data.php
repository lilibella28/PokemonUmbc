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
       

        $sql = "SELECT *, Name FROM pokemon_data";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>";
            echo "ID: " . ($row['ID']) . "<br>";
            echo "Name: " . ($row['Name']) . "<br>";
            echo "Type1: " . ($row['Type1']) . "<br>";
            echo "Type2: " . ($row['Type2']) . "<br>";
            echo "Total: " . ($row['Total']) . "<br>";
            echo "HP: " . ($row['Attack']) . "<br>";
            echo "Defense: " . ($row['Defense']) . "<br>";
            echo "SpAtk: " . ($row['SpAtk']) . "<br>";
            echo "SDef: " . ($row['SpDef']) . "<br>";
            echo "Generation: " . ($row['Generation']) . "<br>";
            echo "Legendary: " . ($row['Legendary']) . "<br>";
            echo "</li>";
        }
    } catch (PDOException $e) {
        echo "<p>connection failed: " . $e->getMessage() . "</p>";
        die();
    } catch (Exception $e) {
        echo "<p>error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>

</html>
