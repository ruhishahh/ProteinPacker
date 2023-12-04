<?php
include 'header.php';
?>
<link rel="stylesheet" href="css/styles.css">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Folder</title>
    <link rel="stylesheet" href="savedRecipes.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .form-container {
            background-color: #fff;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            align-items: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .close-btn {
            text-decoration: none;
            color: #333;
            font-size: 18px;
            float: right;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            width: 95%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #5cb85c;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <a href="savedRecipes.php" class="close-btn">X</a>
        <h2>Create New Folder</h2>
        <form action="createFolder.php" method="post">
            <label for="folderName">Folder Name:</label>
            <input type="text" id="folderName" name="folderName" required>
            <br>
            <button type="submit">Create Folder</button>
        </form>
    </div>
</body>
</html>
