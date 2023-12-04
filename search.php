<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f2f2f2;
        }

        h2 {
            margin-top: 20px;
            color: #333;
        }

        form {
            margin: 20px auto;
            padding: 20px;
            max-width: 400px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        form input[type="text"],
        form input[type="number"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin: 10px;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<?php include 'header.php'; ?>
<body>
    <h2>Search for Recipes</h2>

    <form action="" method="GET">
        <input type="text" name="searchQuery" placeholder="Search for recipes by name...">
        <input type="number" name="proteinContent" placeholder="Enter minimum protein content (g)...">
        <input type="submit" value="Search" style = "background-color: #5cb85c">
    </form>

    <?php
    if (isset($_GET['searchQuery'], $_GET['proteinContent']) && $_GET['searchQuery'] != '' && $_GET['proteinContent'] >= 0) {
        require_once "config.php";
        $conn = get_connection();

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $searchQuery = mysqli_real_escape_string($conn, $_GET['searchQuery']);
        $proteinContent = mysqli_real_escape_string($conn, $_GET['proteinContent']);

        // search query based on name and protein content
        $sql = "SELECT R.* FROM RECIPE R
                JOIN MACRONUTRIENT M ON R.Recipe_ID = M.Recipe_ID
                WHERE R.Name LIKE ? AND M.Protein >= ?";

        $stmt = $conn->prepare($sql);
        $searchTerm = "%$searchQuery%";
        $stmt->bind_param("si", $searchTerm, $proteinContent);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                echo "<p><a href='recipe_details2.php?id=" . $row['Recipe_ID'] . "'>" . htmlspecialchars($row['Name']) . "</a></p>"; 
            }
            echo "</ul>";
        } else {
            echo "<p>No results found for '" . htmlspecialchars($searchQuery) . "' with at least " . htmlspecialchars($proteinContent) . "g of protein</p>";
        }

        $conn->close();
    }
    ?>
</body>
</html>
