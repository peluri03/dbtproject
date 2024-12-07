<?php
session_start();
include('models/db_connect.php');
if (!isset($_SESSION["logged_in"]) && !isset($_SESSION["user_id"])) {
    header("Location: index.php");
    return;
}
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        header("Content-Type: application/json");
        // delete a job
        if (isset($_GET["method"]) && $_GET["method"] === "DELETE") {
            $job_title = $_GET["title"];
            $query = "DELETE FROM jobs WHERE job_title = :job_title";
            $stmt = $db_conn->prepare($query);
            $stmt->execute(array(":job_title" => $job_title));
        } else if (isset($_GET["method"]) && $_GET["method"] === "GET_NO_APPLICANTS") {
            $job_id = $_GET["job_id"];
            $query = "SELECT COUNT(*) as count FROM applied_jobs where job_id = $job_id";
            $stmt = $db_conn->query($query);
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($count);
        } else if (isset($_GET["method"]) && $_GET["method"] === "SEARCH_JOBS") {
            $search_str = $_GET["SEARCH_STR"];
            $query = "SELECT * FROM jobs WHERE job_title LIKE :job_title or company_name LIKE :cmpny_name";
            $stmt = $db_conn->prepare($query);
            $search_str = $search_str . "%";
            $stmt->execute(array(":job_title" => $search_str, ":cmpny_name" => $search_str));
            $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($jobs);
        } else {
        // fetch all jobs
            $query = "SELECT * FROM jobs ORDER BY posted_date DESC";
            $stmt = $db_conn->query($query);
            $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($jobs);
        }
        break;

    case "POST":
        // Apply for the job
        if (isset($_POST["method"]) && $_POST["method"] === "APPLY") {
            header("Content-Type: application/json");
            $job_id = $_POST["job_id"];
            $user_id = $_SESSION["user_id"];
            $query = "SELECT * FROM applied_jobs where user_id = $user_id AND job_id = $job_id";
            $stmt = $db_conn->query($query);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                echo json_encode(array("msg" => "You have already applied for this Job."));
            } else {
                $query = "SELECT * FROM jobs where id = $job_id";
                $stmt = $db_conn->query($query);
                $job = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($job) {
                    $no_of_applicants = $job["no_of_applicants"] + 1;
                    $query = "UPDATE jobs SET no_of_applicants = $no_of_applicants WHERE id = $job_id";
                    $db_conn->query($query);
                }
                $query = "INSERT INTO applied_jobs (user_id, job_id) VALUES($user_id, $job_id)";
                $stmt = $db_conn->query($query);
                echo json_encode(array("msg" => "Applied Successfully"));
            }
        } else {
            $job_title = trim(htmlentities(strip_tags($_POST["job_title"])));
            $company_name = trim(htmlentities(strip_tags($_POST["company_name"])));
            $job_description = trim(htmlentities(strip_tags($_POST["job_description"])));
            $no_of_applicants = 0;

            $query = "INSERT INTO jobs (job_title, company_name, job_description, posted_date, no_of_applicants) VALUES 
                        (:job_title, :company_name, :job_description, CURRENT_TIMESTAMP, :no_of_applicants)";

            $stmt = $db_conn->prepare($query);
            $stmt->execute(array(
                ":job_title" => $job_title,
                ":company_name" => $company_name,
                ":job_description" => $job_description,
                ":no_of_applicants" => $no_of_applicants
            ));

            header("Location: jobs.php");
            break;
        }
}
?>