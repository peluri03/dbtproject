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
		<title>Jobs List</title>
        <link rel="stylesheet" type="text/css" href="styles/style.css" />
        <script src="jquery.js"></script>
	</head>
	<body>
        <div class="navbar">
            <a href="posts.php" class="view_posts_link">View Posts</a>
            <a href="logout.php" class="logout_btn">Logout</a>
            <a href="create_job.php" class="create_job_link">Create Job</a>
            <div class="search_bar">
                <input type="text" class="search_bar_txt" name="search_bar_txt" placeholder="Jobs Search">
            </div>
        </div>
        <div id="jobs_list" class="jobs_list"></div>
	</body>
    <script>
        $(() => {
            $.get("jobs_handler.php", "", (jobs) => {
                var jobs_list = $("#jobs_list");
                jobs_list.html("");
                $.each(jobs, (index, job) => {
                    const job_elem = createJobelem(job);
                    jobs_list.append(job_elem);
                });
            });

            $(".search_bar_txt").on("input", () => {
                var search_content = $(".search_bar_txt").val();
                $.get("jobs_handler.php", {"method": "SEARCH_JOBS", "SEARCH_STR": search_content}, (jobs) => {
                    console.log(jobs);
                    var jobs_list = $("#jobs_list");
                    jobs_list.html("");
                    $.each(jobs, (index, job) => {
                        const job_elem = createJobelem(job);
                        jobs_list.append(job_elem);
                    });
                });
            });

            function createJobelem(job) {
                const job_elem = $("<div></div>").addClass("job");
                const job_title_elem = $("<h2></h2>").text(job.job_title);
                const company_name_elem = $("<p></p>").addClass("job-company-name").text(job.company_name);
                const job_description_elem = $("<pre></pre>").text(job.job_description);
                const date_elem = $("<p></p>").addClass("job-posted-date").text("Posted on " + formatDate(job.posted_date));
                const no_of_applicants_elem = $("<p></p>").addClass("no-of-applicants").text(job.no_of_applicants + " Applied for this Job");
                const button_elem = $("<button>").addClass("apply-for-the-job").text("Apply");
                const response_msg_elem = $("<p></p>").addClass("job-application-msg");
                button_elem.click(() => {
                    $.get("jobs_handler.php", {"job_id": job.id, "method": "GET_NO_APPLICANTS"}, (data) => {
                        no_of_applicants_elem.text(data.count + " Applied for this Job");
                    });
                    $.post("jobs_handler.php", { method: "APPLY", "job_id": job.id }, (data) => { 
                        response_msg_elem.text(data.msg);
                    });
                });
                job_elem.append(job_title_elem, company_name_elem, job_description_elem, date_elem, no_of_applicants_elem, button_elem, response_msg_elem);
                return job_elem;
            }

            function handle_click(job_id, res_elem, no_of_applicants_elem) {
                $.post("jobs_handler.php", {
                    method: "APPLY",
                    "job_id": job_id
                }, (data) => { 
                    res_elem.text(data.msg);
                    if (data["no_of_applicants"]) {
                        no_of_applicants_elem.text(data.no_of_applicants + " Applied for this job");
                    }
                });
            }

            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }
        });
    </script>
</html>