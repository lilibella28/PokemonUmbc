<?php
header('Content-Type: application/json');
include '../../database/pdo.php';

$directory = '../../proj3_images/1st_generation/';

// Get the list of image filenames in the directory
$imageFiles = array_diff(scandir($directory), array('..', '.'));

try {
    $stmt = $conn->prepare("SELECT Name FROM pokemon_data");
    $stmt->execute();

    $pokemonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Filter the Pokémon names to only include those that have a corresponding image
    $filteredPokemonData = array_filter($pokemonData, function($pokemon) use ($imageFiles) {
        $name = strtolower($pokemon['Name']);
        foreach ($imageFiles as $file) {
            // Check if the filename contains the Pokémon name (ignoring digits and case)
            if (stripos($file, $name) !== false) {
                return true;
            }
        }
        return false;
    });

    // Return filtered data as JSON
    echo json_encode(array_values($filteredPokemonData));
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

