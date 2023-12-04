<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <style>
        img {
            max-width: 20%;
            height: auto;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<?php
session_start();
require_once "config.php";
include 'header.php';
$isRecipeSaved = false;

if (isset($_GET['id'])) {
    $recipeId = $_GET['id'];

    // only runs if the saved recipe button was pressed
    if (isset($_GET['saveRecipe'])) {
        $user_id = isset($_SESSION['usrID']) ? $_SESSION['usrID'] : null;
        if (!$user_id) {
            echo "Error: User not logged in.";
            exit;
        }

        $conn = get_connection();
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        

        $sql = "SELECT Folder_ID, FolderName FROM SAVED_RECIPES_FOLDER WHERE User_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $folders = [];
        $folderId = null;

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $folders[] = $row['Folder_ID'];
                $folderId = $row['Folder_ID'];
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();

        if ($folderId) {


            $checkSql = "SELECT * FROM `FOLDER_RECIPES` WHERE `Folder_ID` = ? AND `Recipe_ID` = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ii", $folderId, $recipeId);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $checkStmt->close();
        
            if ($checkResult->num_rows > 0) {
                echo "Error: Recipe already saved.";
            } else {
            $sql = "INSERT INTO `FOLDER_RECIPES` (`Folder_ID`, `Recipe_ID`) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $folderId, $recipeId);

            if ($stmt->execute()) {
                $isRecipeSaved = true; 
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
    }

    require_once "config.php";
    $conn = get_connection();

    $sql = "SELECT * FROM RECIPE WHERE Recipe_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $recipeId);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // displays all the recipe details
        $imageData = $row['Picture'];
        $name = htmlspecialchars($row['Name']);
        $cuisine = htmlspecialchars($row['Cuisine']);
        $dietType = htmlspecialchars($row['Diet Type']);
        $allergen = htmlspecialchars($row['Allergen']);
        $instructions = htmlspecialchars($row['Instructions']);

        // gets the user's name
        $sql = "SELECT R.*, U.Name FROM RECIPE R 
        JOIN USER U ON R.User_ID = U.User_ID 
        WHERE R.Recipe_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $recipeId); 
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $userName = htmlspecialchars($row['Name']);


        echo "<img src='data:image/jpeg;base64," . base64_encode($imageData) . "' alt='Recipe Image'>";
        echo "<h1>" . $name . "</h1>";
        echo "<p><strong>Cuisine:</strong> " . $cuisine . "</p>";        
        echo "<p><strong>Created By:</strong> " . $userName . "</p>";
        echo "<p><strong>Diet Type:</strong> " . $dietType . "</p>";
        echo "<p><strong>Allergen:</strong> " . $allergen . "</p>";
        echo "<p><strong>Instructions:</strong> " . $instructions . "</p>";

        $sql2 = "SELECT * FROM MACRONUTRIENT WHERE Recipe_ID = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param('i', $recipeId);
        $stmt2->execute();
    
        $result2 = $stmt2->get_result();
        $row2 = $result2->fetch_assoc();
    
        // states all the macronutrients associated with the recipe
        if ($row2) {
            $protein = $row2['Protein'];
            $fiber = htmlspecialchars($row2['Fiber']);
            $carb = htmlspecialchars($row2['Carb']);
            $sugar = htmlspecialchars($row2['Sugar']);
            $addedSugar = htmlspecialchars($row2['Added Sugar']);
            $fat = htmlspecialchars($row2['Fat']);
            $saturatedFat = htmlspecialchars($row2['Saturated Fat']);
    
            echo "<p><strong>Protein:</strong> " . $protein . "g" . "</p>";
            echo "<p><strong>Fiber:</strong> " . $fiber . "g" . "</p>";
            echo "<p><strong>Carbs:</strong> " . $carb . "g" . "</p>";
            echo "<p><strong>Sugar:</strong> " . $sugar . "g" . "</p>";
            echo "<p><strong>Added Sugar:</strong> " . $addedSugar . "g" . "</p>";
            echo "<p><strong>Fat:</strong> " . $fat . "g" . "</p>";
            echo "<p><strong>Saturated Fat:</strong> " . $saturatedFat . "g" . "</p>";
        }
        


        if (!$isRecipeSaved) { // if the recipe is not saved, then this button shows up 
            ?>
            <form method="get">
                <input type="hidden" name="id" value="<?= $recipeId ?>">
                <input type="submit" name="saveRecipe" value="Save Recipe">
            </form>
            <?php
        }
    } else {
        echo "<p>Recipe not found.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>No recipe selected.</p>";
}
?>

</body>
</html>
