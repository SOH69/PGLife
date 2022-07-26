<?php
    session_start();

    include "../includes/database.php";

    $name=$_POST['full_name'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
	$pass=sha1($_POST['password']);
	$clg=$_POST['college_name'];
	$gender=$_POST['gender'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }
    $row_count = mysqli_num_rows($result);

    if ($row_count != 0) {
        $response = array("success" => false, "message" => "This email id is already registered with us!");
        echo json_encode($response);
        return;
    }

    $sql = "INSERT INTO users (email, password, full_name, phone, gender, college_name) VALUES ('$email', '$pass', '$name', '$phone', '$gender', '$clg')";
    $result = mysqli_query($conn, $sql);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['full_name'] = $row['full_name'];


    $response = array("success" => true, "message" => "Your account has been created successfully!");
    echo json_encode($response);

    mysqli_close($conn);
?>