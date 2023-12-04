<?php
require_once "config.php";

// Initialize variables to hold values
$recipe_id = $new_name = $new_cuisine = $new_diet_type = "";
$host = 'mariadbprj';
$dbname = 'my_databaseprj';
$username = 'admindatabaseprj';
$password = 'hamouda';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form when it's submitted

    // Retrieve and sanitize the recipe ID
    $recipe_id = filter_input(INPUT_POST, 'recipe_id', FILTER_SANITIZE_NUMBER_INT);

    // Retrieve the new values from the form
    $name = $_POST["new_name"];
    $cuisine = $_POST["new_cuisine"];
    $dietType = $_POST["new_diet_type"];

    if (!empty($recipe_id)) {
        try {
            // Create a PDO instance and establish the database connection.
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

            // Set the PDO error mode to exception for better error handling.
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Build the SQL query dynamically based on the provided values
            $sql = "UPDATE RECIPE SET";
            $params = array();

            if (!empty($name)) {
                $sql .= " Name = :name,";
                $params[':name'] = $name;
            }
            
            if (!empty($cuisine)) {
                $sql .= " Cuisine = :cuisine,";
                $params[':cuisine'] = $cuisine;
            }
            
            if (!empty($dietType)) {
                $sql .= " `Diet Type` = :dietType,";
                $params[':dietType'] = $dietType;
            }

            // Remove the trailing comma and add the WHERE clause
            $sql = rtrim($sql, ",") . " WHERE Recipe_ID = :recipe_id";
            $params[':recipe_id'] = $recipe_id;

            // Prepare and execute the update statement
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            echo "Recipe details updated successfully.";

        } catch (PDOException $e) {
            // Handle any database connection or query errors here.
            echo "Connection failed: " . $e->getMessage();
        }
    } else {
        echo "Recipe ID is a required field.";
    }
}
?>

<!-- HTML Form for updating recipe details -->
<form method="post">
    <p>
        <label for="recipe_id">Recipe ID:</label>
        <input type="number" name="recipe_id" required>
    </p>
    <p>
        <label for="new_name">New Recipe Name:</label>
        <input type="text" name="new_name">
    </p>
    <p>
        <label for="new_cuisine">New Cuisine:</label>
        <input type="text" name="new_cuisine">
    </p>
    <p>
        <label for="new_diet_type">New Diet Type:</label>
        <input type="text" name="new_diet_type">
    </p>
    <p>
        <input type="submit" name="update" value="Update Recipe Details">
    </p>
</form>
