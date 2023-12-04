<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'header.php'; ?>
    <meta charset="UTF-8">
    <title>Recipe Search</title>
</head>
<body>
    <h2>Search for Recipes by Protein Content</h2>

    <!-- Search Form for Protein Content -->
    <form action="" method="GET">
        <input type="number" name="proteinContent" placeholder="Enter minimum protein content (g)...">
        <input type="submit" value="Search">
    </form>

    <?php
    if (isset($_GET['proteinContent']) && $_GET['proteinContent'] > 0) {
        require_once "config.php";
        $conn = get_connection();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $proteinContent = mysqli_real_escape_string($conn, $_GET['proteinContent']);

        // SQL query to find recipes based on protein content
        $sql = "SELECT R.* FROM RECIPE R
                JOIN MACRONUTRIENT M ON R.Recipe_ID = M.Recipe_ID
                WHERE M.Protein >= ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $proteinContent);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                echo "<p><a href='recipe_details2.php?id=" . $row['Recipe_ID'] . "'>" . htmlspecialchars($row['Name']) . "</a></p>"; 
            }
            echo "</ul>";
        } else {
            echo "<p>No recipes found with at least " . htmlspecialchars($proteinContent) . "g of protein</p>";
        }

        $conn->close();
    }
    ?>
</body>
</html>
