<?php
session_start();
include('models/db_connect.php');
if (!isset($_SESSION["logged_in"]) && !isset($_SESSION["user_id"])) {
    header("Location: index.php");
    return;
}
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        // delete a post
        if (isset($_GET["method"]) && $_GET["method"] === "DELETE") {
            $post_title = $_GET["title"];
            $query = "DELETE FROM posts WHERE post_title LIKE :post_title";
            $stmt = $db_conn->prepare($query);
            $stmt->execute(array(":post_title" => $post_title));
        } else {
        // fetch all posts
            $query = "SELECT * FROM posts ORDER BY post_date DESC";
            $stmt = $db_conn->query($query);
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header("Content-Type: application/json");
            echo json_encode($posts);
        }
        break;

    case "POST":
        $post_title = trim(htmlentities(strip_tags($_POST["post_title"])));
        $post_message = trim(htmlentities(strip_tags($_POST["post_message"])));
        $user_id = $_SESSION["user_id"];

        $query = "INSERT INTO posts (post_title, post_message, post_date, user_id) values (:post_title, :post_message, CURRENT_TIMESTAMP, :user_id)";
        $stmt = $db_conn->prepare($query);
        $stmt->execute(array(
            ":post_title" => $post_title,
            ":post_message" => $post_message,
            ":user_id" => $user_id
        ));

        header("Location: posts.php");
        break;
}
?>