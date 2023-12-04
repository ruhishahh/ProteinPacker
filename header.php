<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 

$fullName = isset($_SESSION['fullName']) ? htmlspecialchars($_SESSION['fullName']) : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/header.css"> 
</head>
<body>
    <div id="header-logo">
        <img src="protein.png" alt="Protein Packer Logo">
        <h1>Protein Packer</h1> 
    </div>

    <!-- displays all the top bar content -->
    <div id="topbar">
        <a href="registerRecipeForm.php">Create Recipe</a>
        <a href="savedRecipes.php">Saved Recipes</a>
        <a href="showRecipes.php">All Recipes</a>
        <a href="statistics.php">Statistics</a>
        <a href="accountSettings.php">Account Settings</a>
        <div class="welcome-text">Hi, <?php echo $fullName; ?></div>
        <a href="logOut.php">Log Out</a>
        <a href="search.php">Search For Recipes</a>
    </div>
</body>
</html>
