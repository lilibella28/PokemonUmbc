<?php

header('Content-Type: application/json'); 
include '../../database/pdo.php';

try {
    $stmt = $conn->prepare("SELECT * FROM pokemon_data");
    $stmt->execute();

    $pokemonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($pokemonData);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
