<?php
    $db_hostname = "51.79.162.228";
    $db_username = "soh699";
    $db_password = "soh2022";
    $db_name = "pg-life";

    $conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);
    if (!$conn) {
        echo "Connection failed: " . mysqli_connect_error();
        exit;
    }
?>