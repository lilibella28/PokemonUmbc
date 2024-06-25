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
// Database connection
$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pokemon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the image
$sql = "SELECT Image_name, Image_data FROM pokemon_image WHERE ID = 3"; // Change ID as needed
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $imageName = $row['Image_name'];
        $imageData = $row['Image_data'];

        // Display the image
        echo "<h1>$imageName</h1>";
        echo $imageData;
        echo "base64_encode($imageData)";
        echo '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($imageData) . '" />';
    }
} else {
    echo "No image found.";
}

// Close the database connection
$conn->close();
?>
</body>
</html>