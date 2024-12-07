<?php
// db connection config variables
$username = "if0_37866715";
$password = "Bajiking143";
$db_url = "mysql:host=sql100.infinityfree.com:3306;dbname=if0_37866715_jobsearch;charset=utf8";
try {
    $db_conn = new PDO($db_url, $username, $password);
    if ($db_conn) {
        $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $pe) {
    echo "Error: $pe->getMessage()";
}
?>