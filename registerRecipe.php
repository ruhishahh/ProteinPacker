<?php
session_start();
require_once "config.php";
$conn = get_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_SESSION['usrID']) ? $_SESSION['usrID'] : null;

    if (!$user_id) {
        echo "Error: User not logged in.";
        exit;
    }

    // obtains information from register Recipe Form
    $name = $_POST["name"];
    $cuisine = $_POST["cuisine"];
    $dietType = $_POST["dietType"];
    $allergens = $_POST["allergens"];
    $instructions = $_POST["instructions"];

    // handles the file upload for the picture
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $picture = file_get_contents($_FILES['picture']['tmp_name']);
    } else {
        echo "Error in file upload";
        exit;
    }

    // inserts the Recipe information
    $sql = "INSERT INTO `RECIPE` (`Name`, `Cuisine`, `Diet Type`, `Picture`, `Allergen`, `User_ID`, `Instructions`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssis", $name, $cuisine, $dietType, $picture, $allergens, $user_id, $instructions);

    if ($stmt->execute()) {
        $recipe_id = $conn->insert_id;
    } else {
        echo "Error: " . $stmt->error;
        exit;
    }
    $stmt->close();

    // this checks if the value is empty or not
    function getPostValueOrNull($fieldName) {
        return isset($_POST[$fieldName]) && $_POST[$fieldName] !== '' ? (int)$_POST[$fieldName] : null;
    }

    $protein = getPostValueOrNull('protein');
    $fiber = getPostValueOrNull('fiber');
    $carb = getPostValueOrNull('carb');
    $sugar = getPostValueOrNull('sugar');
    $addedSugar = getPostValueOrNull('addedSugar');
    $fat = getPostValueOrNull('fat');
    $saturatedFat = getPostValueOrNull('saturatedFat');

    // inserts the macronutrient information
    $stmt = $conn->prepare("INSERT INTO `MACRONUTRIENT` (`protein`, `fiber`, `carb`, `sugar`, `Added Sugar`, `fat`, `Saturated Fat`, `Recipe_ID`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiiiii", $protein, $fiber, $carb, $sugar, $addedSugar, $fat, $saturatedFat, $recipe_id);

    if ($stmt->execute()) {
        header("Location: showRecipes.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
