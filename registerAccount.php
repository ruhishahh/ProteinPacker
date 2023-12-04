<?php
require_once "config.php";
$conn = get_connection();


$fullName = $_POST["fullName"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$verification = $_POST["verification"];
$username = $_POST["username"];
$password = $_POST["password"];

// inserts the new user information
$sql = "INSERT INTO `USER` (`Name`, `Phone_number`, `Email`, `Verification`, `Username`, `Password`) VALUES
('$fullName', '$phone', '$email', '$verification', '$username','$password')";

// saves session data
$_SESSION['usrID'] =  $conn->insert_id;
$_SESSION['verification'] = $verification;
$_SESSION['username'] = $username;
$_SESSION['name'] = $fullName;


if ($conn->query($sql) === TRUE) {
    header("Location: showRecipes.php"); 

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>