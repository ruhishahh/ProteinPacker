<?php
session_start();
require_once "config.php";

include 'header.php';

// function picks a random green shade
function getRandomColor() {
    $colors = ['#e6f4ea', '#cceacc', '#99d699', '#66c266', '#33ad33', '#248f24', '#196619', '#0d4d0d'];
    return $colors[array_rand($colors)];
}

$userID = $_SESSION['usrID'];

$folders = [];

$conn = get_connection();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT FolderName, Folder_ID FROM SAVED_RECIPES_FOLDER WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);


if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $folderColor = getRandomColor(); // gets a random green shade for each folder
        $folders[] = ['name' => $row['FolderName'], 'id' => $row['Folder_ID'], 'color' => $folderColor];
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Recipes</title>
    <style>
        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .folder-card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
            color: black; 
        }
        .folder-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .folder-card a {
            text-decoration: none;
            color: inherit; 
            font-weight: bold;
        }

        .add-new-folder-btn {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 20px 40px;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 20px;
        }
        .add-new-folder-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="folder-grid">
        <?php foreach ($folders as $folder): ?>
            <div class="folder-card" style="background-color: <?php echo $folder['color']; ?>">
                <a href='folder_details.php?id=<?php echo $folder['id']; ?>'>
                    <?php echo htmlspecialchars($folder['name']); ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="createFolderForm.php" class="add-new-folder-btn">Add New Folder</a>
</body>
</html>
