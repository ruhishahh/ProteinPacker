<?php
session_start();
require_once "config.php";
include 'header.php';


if (isset($_GET['id'])) {
    $folderId = $_GET['id'];
    $conn = get_connection();

    $sql = "SELECT Recipe_ID FROM FOLDER_RECIPES WHERE Folder_ID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $folderId);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo "<div class='card-container'>";

    // iterate through all the recipes
    foreach ($rows as $row) {
        $recipeId = $row['Recipe_ID'];
        $sql2 = "SELECT Name, Cuisine, Picture FROM RECIPE WHERE Recipe_ID = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param('i', $recipeId);

        if ($stmt2->execute()) {
            $result2 = $stmt2->get_result();
            $row2 = mysqli_fetch_assoc($result2);

            // card for each recipe
            echo "<div class='card'>";

            if (!is_null($row2['Picture'])) {
                $imageData = base64_encode($row2['Picture']);
                echo "<img src='data:image/jpeg;base64," . $imageData . "'/>";
            }

            echo "<div class='container'>";
            echo "<h4><b><a href='recipe_details2.php?id=" . htmlspecialchars($recipeId) . "'>" . htmlspecialchars($row2['Name']) . "</a></b></h4>";
            echo "<p>" . htmlspecialchars($row2['Cuisine']) . "</p>";
            echo "</div>";

            echo "</div>"; 
        } else {
            echo "Error: " . $stmt2->error;
        }
    }

    echo "</div>"; 
} else {
    echo "Error: " . $stmt->error;
}

echo '<link rel="stylesheet" href="css/folder_details.css">';
}
?>
