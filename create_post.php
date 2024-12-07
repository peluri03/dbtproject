<?php
session_start();
include('models/db_connect.php');
if (!isset($_SESSION["logged_in"]) && !isset($_SESSION["user_id"])) {
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Posts List</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css" />
	</head>
	<body>
        <div class="header">
            <h1> Job Board <h1>
        </div>
        <div id="create_post" class="create_post">
            <h1>Create New Post</h1>
            <form action="posts_handler.php" method="post">
                <input type="text" id="post_title" name="post_title" maxlength="255" placeholder="Title"> 
                <textarea id="post_message" name="post_message" rows="20" cols="80" placeholder="Post Message"></textarea> 
                <input type="submit" name="submit">
            </form>
        </div>
	</body>
</html>