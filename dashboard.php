<?php
    session_start();
    require "includes/database.php";

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

    if ($user_id == NULL) {
        header("Location: index.php");
        die();
        return;
    }

    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "Something went wrong!";
        return;
    }
    $row = mysqli_fetch_assoc($result);

    $sql = "SELECT *
        FROM interested_users_properties iup
        INNER JOIN properties p ON iup.property_id = p.id
        WHERE iup.user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "Something went wrong!";
        return;
    }
    $interested_users_properties = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Profile | PG Life</title>

        <link href="css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://use.fontawesome.com/releases/v6.1.1/css/all.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet" />
        <link href="css/common.css" rel="stylesheet" />
        <link href="css/dashboard.css" rel="stylesheet" />
    </head>

    <body>

        <?php
            include "includes/login-signup.php";
            include "includes/header.php";
        ?>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb py-2">
                <li class="breadcrumb-item">
                    <a href="index.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Dashboard
                </li>
            </ol>
        </nav>

        <div class = 'profile-header'><h1>My Profile</h1>
            <div class = 'info'>
                <div class = 'profile-picture'><i class="fa-solid fa-user" id = 'default-pic'></i></div>
                <div class = 'user-info'>
                    <strong><?php echo $row['full_name'] ?></strong><br>
                    <?php echo $row['email'] ?><br>
                    +91 <?php echo $row['phone'] ?><br>
                    <?php echo $row['college_name'] ?><br>
                </div>
                <a href = '#' class = 'edit'>Edit Profile</a>
            </div>
        </div>

        <div class = 'interested'>
            <div class = 'content-interest'>
                <h1>My Interested Properties</h1>
                <?php
                    if (count($interested_users_properties) == 0) {
                ?>
                    <div class="no-property-container">
                        <p>No Interested Properties found.</p>
                    </div>
                <?php
                    } else {
                        foreach ($interested_users_properties as $property) {
                            $property_images = glob("img/properties/".$property['id']."/*");
                ?>
                    <div class = 'interest-1'>
                        <img src = "<?php echo $property_images[0] ?>" class = 'room-pic'>
                        <div class = 'room-info'>
                            <div class = "iconset">
                                <div class = 'rating'>
                                    <?php
                                        $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                                        $avg_rating = round($total_rating, 1);
                                        for($i = 0; $i < round($avg_rating, 0, PHP_ROUND_HALF_DOWN); $i++) { ?>
                                            <i class="fas fa-star" style = 'margin-right: 10px;'></i>
                                    <?php } ?>
                                    <?php if ($avg_rating - (round($avg_rating, 0, PHP_ROUND_HALF_DOWN )) >= 0.4) { ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php } ?>
                                </div>

                                <i class="fas fa-heart" id = "liked"></i>
                            </div>

                            <strong><?php echo $property['name'] ?></strong>
                            <br><div class = 'location'><?php echo $property['address'] ?></div><br>

                            <img src="img/<?php echo $property['gender'] ?>.png" class = 'property-gender'>

                            <div class = 'price'>
                                <div class="rent"><?php echo $property['rent'] ?>/-<div class = 'rent-unit'>per month</div></div>
                                <a href = "property_detail.php" class = 'view-btn'>View</a>
                            </div>
                        </div>
                    </div>
                <?php } } ?>

            </div>

        </div>

        <?php
            include "includes/footer.php";
        ?>

    </body>

</html>