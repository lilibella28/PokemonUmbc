<?php

header('Content-Type: application/json'); 
include '../../database/pdo.php';

try {
    $stmt = $conn->prepare("
        SELECT ut.*, pd.Name 
        FROM UserTeam ut
        JOIN pokemon_data pd ON ut.ID = pd.ID
    ");
    $stmt->execute();

    $userTeamsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Return data as JSON
    echo json_encode($userTeamsData);
} catch(PDOException $e) {
    echo json_encode(["error" => "Error: " . $e->getMessage()]);
}
?>
