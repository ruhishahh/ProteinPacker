<?php
session_start();
require_once "config.php";

$conn = get_connection();


$userID = $_SESSION['usrID'];
$sql = "SELECT * FROM USER WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID); 
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings</title>
    <link rel="stylesheet" href="css/accountSettings.css"> 
</head>
<body>

<?php include 'header.php'; ?> 

<div class="container">
    <h2>Account Settings</h2>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>Name: " . htmlspecialchars($row["Name"]) . "</p>";
            echo "<p>Phone Number: " . htmlspecialchars($row["Phone_number"]) . "</p>";
            echo "<p>Email: " . htmlspecialchars($row["Email"]) . "</p>";
            echo "<p>Username: " . htmlspecialchars($row["Username"]) . "</p>";
        }
        ?>

        <form action="" method="post">
            <label for="newPassword">New Password:</label>
            <input type="password" name="newPassword" id="newPassword" required>
            <input type="submit" name="changePassword" value="Change Password">
        </form>
        <?php
    } else {
        echo "<p>No user information found.</p>";
    }

    // check if the form is submitted
    if (isset($_POST['changePassword'])) {
        $newPassword = $_POST['newPassword'];

        // update the password
        $updateSql = "UPDATE USER SET password = ? WHERE User_ID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $newPassword, $userID);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            echo "<p>Password changed successfully.</p>";
        } else {
            echo "<p>Password change failed.</p>";
        }

        $updateStmt->close();
    }


if (isset($_SESSION['verification']) && $_SESSION['verification'] == '1') {
    $sql = "SELECT User_ID, Name FROM USER"; 
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<form action="accountSettings.php" method="post">';
        echo '<select name="selectedUser">';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row["User_ID"]) . '">' . htmlspecialchars($row["Name"]) . '</option>';
        }
        echo '</select>';

        echo '<select name="verificationStatus">';
        echo '<option value="1">Make Admin</option>';
        echo '<option value="0">Demote</option>';
        echo '</select>';

        echo '<input type="submit" name="updateVerification" value="Update Verification">';
        echo '</form>';
    } else {
        echo "No users found";
    }
}

if (isset($_POST['updateVerification'])) {
    $selectedUser = $_POST['selectedUser'];
    $verificationStatus = $_POST['verificationStatus']; // '0: not admin' or '1: admin'

    // updates verification status
    $updateVerificationSql = "UPDATE USER SET Verification = ? WHERE User_ID = ?";
    $updateVerificationStmt = $conn->prepare($updateVerificationSql);
    $updateVerificationStmt->bind_param("si", $verificationStatus, $selectedUser);
    $updateVerificationStmt->execute();

    if ($updateVerificationStmt->affected_rows > 0) {
        echo "<p>User verification updated successfully.</p>";
    } else {
        echo "<p>Error updating user verification.</p>";
    }

    $updateVerificationStmt->close();
}
    $conn->close();
    ?>
</div>
</body>
</html>
