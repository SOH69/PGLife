<?php
    session_start();

    include "../includes/database.php";

    $email = $_GET['email'];
	$pass = sha1($_GET['password']);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        header("Location: ../index.php?msg=UserName not found");
        die();
    }

    if ($row['password'] == $pass) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['full_name'] = $row['full_name'];
        header("Location: ../index.php");
        die();
    } else {
        header("Location: ../index.php?msg=$pass");
        die();
    }

    mysqli_free_result($result);
    mysqli_close($conn);
?>