<?php
// db connection config variables
$username = "root";
$password = "";
$db_url = "mysql:host=localhost:3306;dbname=jobs_search;charset=utf8";
try {
    $db_conn = new PDO($db_url, $username, $password);
    if ($db_conn) {
        $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $pe) {
    echo "Error: $pe->getMessage()";
}
?>
