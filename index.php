<?php
session_start();
if (isset($_SESSION["logged_in"]) && isset($_SESSION["user_id"])) {
    header("Location: posts.php");
    return;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login to your Account</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css" />
	</head>
	<body>
        <div class="header">
            <h1> Job Board <h1>
        </div>
		<div class="login">
			<h1>Login to your Account</h1>
			<form action="index.php" method="post" autocomplete="off">
				<input type="email" name="username" placeholder="Username" id="username" required>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login" name="submit">
                <button onclick="location.href='register.php'">Register</button><br><br>
			</form>
            <?php
                if (isset($_SESSION["error_msg"])) {
                    echo $_SESSION["error_msg"];
                    unset($_SESSION["error_msg"]);
                }
            ?>
            <?php
                include("models/db_connect.php");
                if (isset($_POST["submit"]) && isset($_POST["username"]) && isset($_POST["password"])) {
                    $username = trim(htmlentities(strip_tags($_POST["username"])));
                    $password = trim(htmlentities(strip_tags($_POST["password"])));

                    $query = "SELECT * from users WHERE username = :username";
                    $stmt = $db_conn->prepare($query);
                    $stmt->execute(array(":username" => $username));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        if (password_verify($password, $row["password"])) {
                            $_SESSION["logged_in"] = true;
                            $_SESSION["user_id"] = $row["id"];
                            header("Location: posts.php");
                            return;
                        } else {
                            $_SESSION["error_msg"] = "<p style='color:red; text-align=center'>Username or password doesn't match</p>";
                            header("Location: index.php");
                        }
                    } else {
                        $_SESSION["error_msg"] = "<p style='color:red; text-align=center'> Username doesn't exist<p>";
                        header("Location: index.php");
                    }
                }
            ?>
		</div>
	</body>
</html>