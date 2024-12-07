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
		<title>Create Job</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css" />
	</head>
	<body>
        <div class="header">
            <h1> Job Board <h1>
        </div>
        <div id="create_job" class="create_job">
            <h1>Create New Job</h1>
            <form action="jobs_handler.php" method="post">
                <input type="text" id="job_title" name="job_title" class="job_title" placeholder="Job Title">
                <input type="text" id="company_name" name="company_name" class="company_name" placeholder="Company Name">
                <textarea id="job_description" name="job_description" rows="20" cols="80" placeholder="Job Description"></textarea>
                <input type="submit" value="Create Job">
            </form>
        </div>
	</body>
</html>