<!DOCTYPE html>
<?php
session_start();
require_once "config.php";
?>

<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Garamond:wght@400&display=swap">
    <link rel="stylesheet" href="css/registerRecipe.css">
    <title>Register Account</title>
    <style>
        body {
            font-family: 'Garamond', serif;
        }
    </style>
    
    <?php include 'header.php'; ?>
    <script>
        function childToggled() {
            let checkbox = document.getElementById("childCheckbox");
            let additionalDiv = document.getElementById("adultInfo");
            let additionalField = document.getElementById("adultEmail");

            if (checkbox.checked) {
                additionalDiv.style.display = "table-row";
                additionalField.required = true;
            } else {
                additionalDiv.style.display = "none";
                additionalField.required = false;
            }
        }
    </script>
</head>
<body>
    <div id="main" style="width:425px; margin:auto; padding-top:5px;">
        <div style="margin-bottom:1em;">
            <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='error'>" . $_SESSION['error'] . "</p>";
                    unset($_SESSION['error']);
                }
            ?>
        </div>

        <form action="registerRecipe.php" method="post" enctype="multipart/form-data" autocomplete="on" class="tableForm">
            <p class="tableForm">
                <label class="tableForm">Name:</label>
                <input type="text" name="name" required="true" class="joinedInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Cuisine:</label>
                <input type="text" name="cuisine" required="true" class="soloInput">
            </p>
            
            <p class="tableForm">
                <label class="tableForm">Diet Type:</label>
                <input type="text" name="dietType" required="true" class="soloInput">
            </p>
            
            <p class="tableForm">
                <label class="tableForm">Picture:</label>
                <input type="file" name="picture" required="true" accept="image/*" class="soloInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Allergens:</label>
                <input type="text" name="allergens" required="true" class="soloInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Protein:</label>
                <input type="number" name="protein" required="true" class="soloInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Fiber:</label>
                <input type="number" name="fiber" required="true" class="soloInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Carb:</label>
                <input type="number" name="carb" required="true" class="soloInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Sugar:</label>
                <input type="number" name="sugar" required="true" class="soloInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Added Sugar:</label>
                <input type="number" name="addedSugar" required="true" class="soloInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Fat:</label>
                <input type="number" name="fat" required="true" class="soloInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Saturated Fat:</label>
                <input type="number" name="saturatedFat" required="true" class="soloInput">
            </p>

            <p class="tableForm">
                <label class="tableForm">Instructions:</label>
                <textarea name="instructions" required="true" class="soloInput instructions"></textarea>
            </p>
            <br/>
            <input class="link" type="submit" name="register" value="Register">
        </form>
    </div>
</body>
</html>
