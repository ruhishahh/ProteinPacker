<?php
session_start();
require_once "config.php";


try {
    $conn = get_connection();

    $sql = "SELECT Name, Cuisine, Recipe_ID FROM RECIPE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result(); // Get the result set from the prepared statement

    include 'header.php'; 

    echo "<table border='1'>";
    echo "<tr><th>Name</th><th>Cuisine</th></tr>";

    // Use mysqli_fetch_all to fetch all rows as an associative array
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);


    foreach ($rows as $row) {
        echo "<tr><td><a href='recipe_details2.php?id=" . $row['Recipe_ID'] . "'>" . htmlspecialchars($row['Name']) . "</a></td><td>" . htmlspecialchars($row['Cuisine']) . "</td></tr>"; 
    }

    echo "</table>";

    // Don't forget to free the result set
    mysqli_free_result($result);

    echo "</body>";
    echo "</html>";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} finally {
    // Don't forget to close the statement
    $stmt->close();

    // Close the connection
    $conn->close();
}
?>
