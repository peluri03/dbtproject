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
        <script src="jquery.js"></script>
	</head>
	<body>
        <div class="navbar">
            <a href="jobs.php" class="view_jobs_link">View Jobs</a>
            <a href="logout.php" class="logout_btn">Logout</a>
            <a href="create_post.php" class="create_post_link">Create Post</a>
        </div>
        <div id="posts_list" class="posts_list"></div>
	</body>
    <script>
        $(() => {
            $.get("posts_handler.php", "", (posts) => {
                var posts_list = $("#posts_list");
                posts_list.html("");
                $.each(posts, (index, post) => {
                    const post_elem = createPostelem(post);
                    posts_list.append(post_elem);
                })
            });

            function createPostelem(post) {
                const post_elem = $("<div></div>").addClass("post");
                const title_elem = $("<h2></h2>").text(post.post_title);
                const content_elem = $("<pre></pre>").text(post.post_message);
                const date_elem = $("<p></p>").addClass("post-date").text("Posted on " + formatDate(post.post_date));

                const button_elem = $("<button>").text("Delete Post").addClass("delete_post_btn");
                button_elem.click(() => {handle_click(post.post_title)});

                const comment_form = $('<form method="post" action="comments_handler.php"></form>').addClass("comment_form").css("display", "none");
                comment_form.append('<textarea id="comment_msg" class="comment_msg" name="comment_msg" rows="4" cols="40" required></textarea>');
                comment_form.append('<input type="submit" value="submit" name="submit">');
                comment_form.append('<input name="post_id" type="hidden" value="' + post.id + '">');

                const comment_btn_elem = $("<button>").addClass("comment_btn").text("Comment");
                comment_btn_elem.click(() => {
                    if (comment_form.css("display") === "none") {
                        comment_form.show();
                        comment_btn_elem.after(comment_form);
                    } else {
                        comment_form.hide();
                    }
                });

                const comments_list_btn = $("<button>").addClass("comments_list_btn").text("View Comments");
                var comments_list = $("<div></div>").addClass("comments_list").css("display", "none");
                get_comments_list(post.id, comments_list);
                comments_list_btn.click(() => {
                    if (comments_list.css('display') === "none") {
                        comments_list.show();
                    } else {
                        comments_list.hide();
                    }
                });
                post_elem.append(title_elem, content_elem, date_elem, button_elem, comment_btn_elem, comments_list_btn, comments_list);
                return post_elem;
            }

            function handle_click(post_title) {
                $.get("posts_handler.php", {
                    method: "DELETE",
                    title: post_title
                }, () => { location.reload(); });
            }

            function get_comments_list(post_id, comments_list) {
                console.log("PostID: " + post_id);
                $.get("comments_handler.php", {"post_id": post_id}, (comments) => {
                    $.each(comments, (index, comment) => {
                        const cmt_elem = createCommentElem(comment);
                        comments_list.append(cmt_elem);
                    })
                });
            }

            function createCommentElem(comment) {
                const cmt_elem = $("<div></div>").addClass("comment");
                const cmt_txt = $("<p></p>").addClass("comment_msg").text(comment.comment);
                const cmt_time_elem = $("<p></p>").addClass("comment_date").text("Commented on " + formatDate(comment.comment_time));
                cmt_elem.append(cmt_txt, cmt_time_elem);
                return cmt_elem;
            }

            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }
        });
    </script>
</html>