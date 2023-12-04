<?php
session_start();
require_once "config.php";

try {
    $conn = get_connection();

    $sql = "SELECT Name, Cuisine, Recipe_ID, Picture FROM RECIPE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result(); 

    echo '<link rel="stylesheet" href="css/showRecipes.css">';
    include 'header.php';

    echo "<div class='card-container'>";

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($rows as $row) {
        echo "<div class='card'>";
  
        if (!is_null($row['Picture'])) {
            $imageData = base64_encode($row['Picture']);
            echo "<img src='data:image/jpeg;base64," . $imageData . "'/>";
        }

        echo "<div class='container'>";
        echo "<h4><b><a href='recipe_details2.php?id=" . $row['Recipe_ID'] . "'>" . htmlspecialchars($row['Name']) . "</a></b></h4>";
        echo "<p>" . htmlspecialchars($row['Cuisine']) . "</p>";
        echo "</div>";
        echo "</div>";
    }

    echo "</div>"; 

    mysqli_free_result($result);

    echo "</body>";
    echo "</html>";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} finally {
    $stmt->close();
    $conn->close();
}
?>
