<?php
session_start();
include("models/db_connect.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Create Your Account</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css" />
	</head>
	<body>
        <div class="header">
            <h1> Job Board <h1>
        </div>
		<div class="register">
			<h1>Create New Account</h1>
			<form action="register.php" method="post" autocomplete="off">
				<input type="email" name="username" placeholder="Username" id="username" required>
				<input type="password" name="password" placeholder="Password" id="password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required>
				<input type="submit" value="Register" name="submit">
                <button onclick="location.href='index.php'">Login</button><br><br>
			</form>
			<?php
				if (isset($_SESSION["error_msg"])) {
					echo $_SESSION["error_msg"];
					unset($_SESSION["error_msg"]);
				}
			?>
		</div>
		<?php 
			if (isset($_POST["submit"])) {
				if (isset($_POST["username"]) && isset($_POST['password'])) {
					$username = trim(htmlentities(strip_tags($_POST["username"])));
					$password = trim(htmlentities(strip_tags($_POST["password"])));
					$confirm_password = trim(htmlentities(strip_tags($_POST["confirm_password"])));
					if ($password !== $confirm_password) {
						$_SESSION["error_msg"] = "<p style='color:red'>Passwords doesn't match</p>";
						header("Location: register.php");
					} else {
						$pass_hash = password_hash($password, PASSWORD_DEFAULT);

						// check if username already exists
						$query = "SELECT * FROM users WHERE username = :username";
						$stmt = $db_conn->prepare($query);
						$stmt->execute( array( 'username' => $username ) );
						$cnt = $stmt->rowCount();
						if ($cnt > 0) {
							$_SESSION["error_msg"] = "<p style='color:red; text-align=center'>Username exists, please choose another!</p>";
							header("Location: register.php");
						} else {
							$query = "INSERT INTO users (username, password) VALUES (:username, :password)";
							$stmt = $db_conn->prepare($query);
							$cnt = $stmt->execute( array('username' => $username, 'password' => $pass_hash ) );
							header("Location: index.php");
							return;
						}
					}
				}
			}
		?>
	</body>
</html>