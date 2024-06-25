<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokemon Database</title>
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

        // Create table with the structure create_database.sql
        $sql = file_get_contents('../database/create_database.sql');
        $conn->exec($sql);
        echo "<p>Table 'pokemon' created successfully</p>";

        // Import CSV data to phpAdmin
        $data_file = '../proj3_images/pokemon_data.csv';

        if (($handle = fopen($data_file, "r")) !== FALSE) {
            fgetcsv($handle, 1000, ",");


            //Insert data into the table
            $sql = "INSERT IGNORE INTO pokemon_data (ID, Name, Type1, Type2, Total, HP, Attack, Defense, SpAtk, SpDef, Speed, Generation, Legendary)
                    VALUES (:ID, :Name, :Type1, :Type2, :Total, :HP, :Attack, :Defense, :SpAtk, :SpDef, :Speed, :Generation, :Legendary)";
            $stmt = $conn->prepare($sql);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $legendary = ($data[12] == 'TRUE') ? 1 : 0;
                $stmt->execute([
                    ':ID' => $data[0],
                    ':Name' => $data[1],
                    ':Type1' => $data[2],
                    ':Type2' => $data[3],
                    ':Total' => $data[4],
                    ':HP' => $data[5],
                    ':Attack' => $data[6],
                    ':Defense' => $data[7],
                    ':SpAtk' => $data[8],
                    ':SpDef' => $data[9],
                    ':Speed' => $data[10],
                    ':Generation' => $data[11],
                    ':Legendary' => $legendary
                ]);
            }

            fclose($handle);
            echo "<p>Data imported</p>";
        } else {
            echo "<p>error opening the file.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Connection failed: " . $e->getMessage() . "</p>";
        die();
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>

</html>