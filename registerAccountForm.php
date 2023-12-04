<!DOCTYPE html>

<?php
    require_once "config.php";
	include 'loggedOutheader.php';
?>

<html>
<head>
    <title>Register Account</title>
    <link rel="stylesheet" href="css/registerAccount.css">
</head>

<body>
	<div id="header">
		<h1> </h1>
	</div>

	<!-- shows form for all the attributes for User -->
	<div id="main" style="width:425px; margin:auto; padding-top:5px;">

		<form action="registerAccount.php" method="post" autocomplete="off" class="tableForm">
			<p class="tableForm">
				<label class="tableForm">Name:</label>
				<input type="text" name="fullName" required="true" class="soloInput">
			</p>

			<p class="tableForm">
				<label class="tableForm">Email:</label>
				<input type="email" name="email" required="true" class="soloInput">
			</p>
			
			<p class="tableForm">
				<label class="tableForm" style="padding-right:5px;">Phone Number:</label>
				<input type="tel" name="phone" class="soloInput">
			</p>
			
			<p class="tableForm">
				<label class="tableForm">Username:</label>
				<input type="username" name="username" required="true" class="soloInput">
			</p>

			<p class="tableForm">
				<label class="tableForm">Password:</label>
				<input type="password" name="password" required="true" class="soloInput">
			</p>

			<input type="hidden" name="verification" value="0">

			<br/>

			<input class="link" type="submit" name="register" value="Register">
			<div style="margin-bottom:1em;">
			<p class="login">Already have a Protein Packer account? <a href="index.php" class="signin">Sign In</a></p>
			<?php
				if (isset($_SESSION['error']))
				{
					echo "<p class='error'>" . $_SESSION['error'] . "</p>";
					unset($_SESSION['error']);
				}
			?>
		</div>
		</form>
	</div>
</body>
</html>