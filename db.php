<?php

mysqli_report(
    MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT
);

try {

    $conn = new mysqli(
        "localhost",
        "root",
        "",
        "internship_db"
    );

} catch (Exception $e) {

    die("Database connection failed.");
}
?>