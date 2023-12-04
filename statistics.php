<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Verification Statistics</title>
    <style>

        
        * {
        font-family: 'Garamond', serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        .table-container {
            padding: 20px;
            margin: 0 auto;
            max-width: 800px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            width: 30%;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>


    <?php
    session_start();
    require_once "config.php";
    $conn = get_connection();
    include 'header.php';

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "<div class = 'table-container'>";

    $sql = "SELECT Verification, COUNT(*) AS NumberOfUsers FROM USER GROUP BY Verification";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<h2>Verified Users</h2>";
        echo "<tr><th>Verification</th><th>Number of Users</th></tr>";

        while($row = $result->fetch_assoc()) {
            if (htmlspecialchars($row["Verification"]) == '0') {
                echo "<tr><td>" . "User" . "</td><td>" . htmlspecialchars($row["NumberOfUsers"]) . "</td></tr>";
            }
            else {
                echo "<tr><td>" . "Admin" . "</td><td>" . htmlspecialchars($row["NumberOfUsers"]) . "</td></tr>";
            }
    }

        echo "</table>";
    } else {
        echo "<p>0 results</p>";
    }

$userID = $_SESSION['usrID'];

//finds the favorite cuisines of the user
$sql_cuisine = "SELECT R.Cuisine, COUNT(*) AS NumberOfTimes 
                FROM USER U
                JOIN SAVED_RECIPES_FOLDER SF ON U.User_ID = SF.User_ID
                JOIN FOLDER_RECIPES FR ON SF.Folder_ID = FR.Folder_ID
                JOIN RECIPE R ON FR.Recipe_ID = R.Recipe_ID
                WHERE U.User_ID = ?
                GROUP BY R.Cuisine
                ORDER BY NumberOfTimes DESC";

$stmt = $conn->prepare($sql_cuisine);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result_cuisine = $stmt->get_result();

if ($result_cuisine->num_rows > 0) {
    echo "<h2>User Favorite Cuisines</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Cuisine Type</th><th>Number of Recipes Saved For This Cuisine</th></tr>";

    while($row = $result_cuisine->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row["Cuisine"]) . "</td><td>" . htmlspecialchars($row["NumberOfTimes"]) . "</td></tr>";
    }

    echo "</table>";
} else {
    echo "<p>No cuisine preferences found for this user.</p>";
}
echo "<p></p>";



//-----------------------------------------------------------------------------------------------------------------------------------------
// finds the number of recipes made by the current user
$userRecipesQuery = "SELECT COUNT(*) AS UserRecipesCount FROM RECIPE WHERE User_ID = ?";
$stmtUserRecipes = $conn->prepare($userRecipesQuery);
$stmtUserRecipes->bind_param("i", $userID);
$stmtUserRecipes->execute();
$resultUserRecipes = $stmtUserRecipes->get_result();
$userRecipesCount = ($resultUserRecipes->fetch_assoc())['UserRecipesCount'];

// calculates the average number of recipes per user
$avgRecipesQuery = "SELECT COUNT(*) / COUNT(DISTINCT User_ID) AS AvgRecipesPerUser FROM RECIPE";
$resultAvgRecipes = $conn->query($avgRecipesQuery);
$avgRecipesPerUser = ($resultAvgRecipes->fetch_assoc())['AvgRecipesPerUser'];

// finds the total number of recipes overall on the website
$totalRecipesQuery = "SELECT COUNT(*) AS TotalRecipesCount FROM RECIPE";
$resultTotalRecipes = $conn->query($totalRecipesQuery);
$totalRecipesCount = ($resultTotalRecipes->fetch_assoc())['TotalRecipesCount'];
$otherUsersRecipesCount = $totalRecipesCount - $userRecipesCount;

echo "<h2>Recipe Statistics</h2>";
echo "<table border='1'>";
echo "<tr><th>Recipes by Current User</th><th>Average Recipes per User</th><th>Recipes by Other Users</th></tr>";
echo "<tr><td>$userRecipesCount</td><td>$avgRecipesPerUser</td><td>$otherUsersRecipesCount</td></tr>";
echo "</table>";

//----------------------------------------------------------------------------------------------------------------------------------------

// number of saved recipes by diet type query
$dietTypeQuery = "SELECT R.`Diet Type`, COUNT(*) AS NumberOfRecipes 
                  FROM USER U
                  JOIN SAVED_RECIPES_FOLDER SF ON U.User_ID = SF.User_ID
                  JOIN FOLDER_RECIPES FR ON SF.Folder_ID = FR.Folder_ID
                  JOIN RECIPE R ON FR.Recipe_ID = R.Recipe_ID
                  WHERE U.User_ID = ?
                  GROUP BY R.`Diet Type`";

$stmtDietType = $conn->prepare($dietTypeQuery);
$stmtDietType->bind_param("i", $userID);
$stmtDietType->execute();
$resultDietType = $stmtDietType->get_result();

// show the fourth table
echo "<h2>User's Saved Recipes by Diet Type</h2>";
echo "<table border='1'>";
echo "<tr><th>Diet Type</th><th>Number of Saved Recipes</th></tr>";

while ($row = $resultDietType->fetch_assoc()) {
    echo "<tr><td>" . htmlspecialchars($row["Diet Type"]) . "</td><td>" . htmlspecialchars($row["NumberOfRecipes"]) . "</td></tr>";
}

echo "</table>";
echo "</div>";
//----------------------------------------------------------------------------------------------------------------------------------------

$conn->close();
?>

</body>
</html>
