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
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pokemon";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch and display Name and ID from the database
        $sql = "SELECT ID, Name, HP FROM pokemon_data";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        echo "<ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>";
            echo "ID: " . ($row['ID']) . "<br>";
            echo "HP: ". $row["HP"] ."<br>";
            echo "Name: " . ($row['Name']) . "<br>";
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
