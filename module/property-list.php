<?php
    session_start();
    include "includes/database.php";

    $city = $_GET['city'];
    $city = strtolower($city);

    $sql = "SELECT * FROM cities WHERE name = '$city'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);
    if ($row >= 1) {
        $city = ucfirst($city);
        header("Location: #?city=$city");
        die();
    } else {
        header("Location: ../index.php");
        die();
        return;
    }

    $city_id = $_GET['id'];
    $sql_1 = "SELECT * FROM properties WHERE city_id = '$city_id'";
    $result_1 = mysqli_query($conn, $sql_1);
    if (!$result_1) {
        echo "Something went wrong!";
        return;
    }
    $properties = mysqli_fetch_all($result_1, MYSQLI_ASSOC);

?>