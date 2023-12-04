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

    $folderName = $_POST["folderName"];

    $sql = "INSERT INTO `SAVED_RECIPES_FOLDER` (`FolderName`, `User_ID`) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $folderName, $user_id);

    if ($stmt->execute()) {
        header("Location: savedRecipes.php");
       
    } else {
        echo "Error: " . $stmt->error;
        exit;
    }
    
    $stmt->close();
    $conn->close();
}
?>
