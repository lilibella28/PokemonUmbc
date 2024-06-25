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
        echo "<p>Table created successfully</p>";
        $folder_image = opendir("../proj3_images/1st_Generation");

        while (($file = readdir($folder_image)) !== false) {
            $img_data_path = $folder_image . "/" . $file;
            if (is_file($img_data_path) && in_array(mime_content_type($img_data_path), ['image/jpeg', 'image/png', 'image/gif'])) {

                $img_data = (file_get_contents($img_data_path));
                $img_data = addslashes(file_get_contents($image)); 
                $stmt = $conn->prepare("INSERT INTO pokemon_image (Image_name, Image_data) VALUES (:Image_name, :Image_data)");
                $stmt->bindParam(':Image_name', $file);
                $stmt->bindParam(':Image_data', $img_data, PDO::PARAM_LOB);
                echo $img_data;
                // Execute the prepared statement
                if ($stmt->execute()) {
                    echo "Image $file uploaded successfully<br>";
                } else {
                    echo "Error uploading image $file: " . $stmt->errorInfo()[2] . "<br>";
                }
            }
        }

        closedir($folder_image);
    } catch (PDOException $e) {
        echo "<p>Connection failed: " . $e->getMessage() . "</p>";
        die();
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>

</html>