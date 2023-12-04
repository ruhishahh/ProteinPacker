<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/index.css">
    <?php include 'loggedOutheader.php'?>
    <h1> </h1>
</head>
<body>
<?php
    require_once "config.php";

    // check if the user is already logged in
    if (isset($_SESSION['usrID']))
    {
        header("Location: /frontend/showRecipes.php");
        exit;
    }

    // check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = get_connection();  
 
        $username = $_POST["username"];
        $password = $_POST["password"];


        $sql = "SELECT * FROM USER WHERE Username = '$username' AND Password = '$password'";
        $result = $conn->query($sql);

        $sql2 = "SELECT User_ID, Verification, Name FROM USER WHERE Username = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $username);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2 && $result2->num_rows == 1) {
            $row = $result2->fetch_assoc();
            $_SESSION['usrID'] = $row['User_ID'];
            $_SESSION['verification'] = $row['Verification'];
            $_SESSION['fullName'] = $row['Name'];
        } else {
        }


        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['username'] = $row['Username'];

            header("Location: /frontend/showRecipes.php");
            exit;
        } else {
            $loginError = "Invalid username or password";
        }

        $conn->close();
    }
?>

<div id="main" style="width:425px; margin:auto; padding-top:5px;">
    <div style="margin-bottom:1em;">
        <p class = "register">Don't have an account? <a href="registerAccountForm.php">Register</a></p>
        <?php
            if (isset($loginError)) {
                echo "<p class='error'>" . $loginError . "</p>";
            }
        ?>
    </div>

    <form action="" method="post" autocomplete="off" class="tableForm">
        <p class="tableForm">
            <label class="tableForm">Username:</label>
            <input type="text" name="username" required="true" class="soloInput">
        </p>

        <p class="tableForm">
            <label class="tableForm">Password:</label>
            <input type="password" name="password" required="true" class="soloInput">
        </p>

        <br/>

        <input class="link" type="submit" name="login" value="Login">
    </form>
</div>

</body>
</html>
