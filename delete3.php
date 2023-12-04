<?php

try {
    // Create a PDO instance and establish the database connection.
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception for better error handling.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Now you can use $pdo for database operations, such as prepare statements.
    
    // Example: Delete data using a prepared statement.
    //$recipe_id = 49; // Correct variable name
    $recipe_id = intval($_POST['recipe_id']);
    echo $recipe_id;
    $stmt = $pdo->prepare("DELETE FROM RECIPE WHERE Recipe_ID = :recipe_id");
    $stmt->bindParam(':recipe_id', $recipe_id);
    $stmt->execute();
    
} catch (PDOException $e) {
    // Handle any database connection or query errors here.
    echo "Connection failed: " . $e->getMessage();
}
?>
