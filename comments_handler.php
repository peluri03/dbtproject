<?php
session_start();
include('models/db_connect.php');

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        // delete a comment
        if (isset($_GET["method"]) && $_GET["method"] === "DELETE") {
            $post_title = $_GET["title"];
            $query = "DELETE FROM posts WHERE post_title LIKE :post_title";
            $stmt = $db_conn->prepare($query);
            $stmt->execute(array(":post_title" => $post_title));
        } else {
        // fetch all comments
            $post_id = $_GET["post_id"];
            $query = "SELECT * FROM comments WHERE post_id = $post_id ORDER BY comment_time";
            $stmt = $db_conn->query($query);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header("Content-Type: application/json");
            echo json_encode($comments);
        }
        break;

    case "POST":
        $comment_msg = $_POST["comment_msg"];
        $post_id = $_POST["post_id"];
        $user_id = $_SESSION["user_id"];

        $query = "INSERT INTO comments (comment, comment_time, post_id, user_id) values (:comment, CURRENT_TIMESTAMP, :post_id, :user_id)";
        $stmt = $db_conn->prepare($query);
        $stmt->execute(array(
            ":comment" => $comment_msg,
            ":post_id" => $post_id,
            ":user_id" => $user_id
        ));

        header("Location: posts.php");
        break;
}
?>