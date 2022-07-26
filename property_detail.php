<?php
    session_start();
    require "includes/database.php";

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
    $property_name = $_GET['name'];
    $property_id = $_GET['id'];

    $sql = "SELECT *
        FROM properties_amenities pa
        INNER JOIN properties p ON pa.property_id = p.id
        INNER JOIN amenities a ON pa.amenity_id = a.id
        WHERE p.id = $property_id";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        echo "Something went wrong!";
        return;
    }
    $details = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $sql_1 = "SELECT * FROM properties WHERE id = '$property_id'";
    $result_1 = mysqli_query($conn, $sql_1);
    if (!$result_1) {
        echo "Something went wrong!";
        return;
    }
    $property = mysqli_fetch_assoc($result_1);

    $sql_3 = "SELECT *
        FROM interested_users_properties iup
        INNER JOIN properties p ON iup.property_id = p.id
        WHERE p.id = $property_id";
    $result_3 = mysqli_query($conn, $sql_3);
    if (!$result_3) {
        echo "Something went wrong!";
        return;
    }
    $interested_users_properties = mysqli_fetch_all($result_3, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ganpati Paying Guest | PG Life</title>

    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet" />
    <link href="css/common.css" rel="stylesheet" />
    <link href="css/property_detail.css" rel="stylesheet" />
</head>

<body>

    <?php
        include "includes/login-signup.php";
        include "includes/header.php";
    ?>

    <div id="loading">
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="property_list.php?city=<?php echo $_GET['city']; ?>"><?php echo $_GET['city']; ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $_GET['name']; ?>
            </li>
        </ol>
    </nav>

    <div id="property-images" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php
                $property_images = glob("img/properties/".$property['id']."/*");
                for($i = 0; $i < count($property_images); $i++) {
                    if ($i == 0) {
            ?>
                    <li data-target="#property-images" data-slide-to= <?php echo $i ?> class="active"></li>
            <?php
                } else {
            ?>
                <li data-target="#property-images" data-slide-to= <?php echo $i ?> class=""></li>
            <?php
                    }
                }
            ?>
        </ol>
        <div class="carousel-inner">

            <?php
                $property_images = glob("img/properties/".$property['id']."/*");
                for($i = 0; $i < count($property_images); $i++) {
            ?>
                <div class="carousel-item active">
                    <img class="d-block w-100" src = <?php echo $property_images[$i] ?> alt="slide">
                </div>
            <?php
                }
            ?>
        </div>
        <a class="carousel-control-prev" href="#property-images" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#property-images" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="property-summary page-container">
        <div class="row no-gutters justify-content-between">
            <?php
                $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                $avg_rating = round($total_rating, 1);
            ?>
            <div class="star-container" title="<?php echo $avg_rating?>">
                <?php for($i = 0; $i < round($avg_rating, 0, PHP_ROUND_HALF_DOWN); $i++) { ?>
                    <i class="fas fa-star"></i>
                <?php } ?>
                <?php if ($avg_rating - (round($avg_rating, 0, PHP_ROUND_HALF_DOWN )) >= 0.4) { ?>
                    <i class="fas fa-star-half-alt"></i>
                <?php } ?>
            </div>
            <div class="interested-container">
                <i class="is-interested-image far fa-heart"></i>
                <div class="interested-text">
                    <?php $count = 0;
                        for($i = 0; $i < count($interested_users_properties); $i++){
                            if ($interested_users_properties[$i]['property_id'] == $property['id'] ){
                                $count++;
                            }
                        }
                    ?>
                    <span class="interested-user-count"><?php echo $count ?></span> interested
                </div>
            </div>
        </div>
        <div class="detail-container">
            <div class="property-name"><?php echo $property['name'] ?></div>
            <div class="property-address"><?php echo $property['address'] ?></div>
            <div class="property-gender">
                <img src="img/<?php echo $property['gender'] ?>.png" />
            </div>
        </div>
        <div class="row no-gutters">
            <div class="rent-container col-6">
                <div class="rent"><?php echo $property['rent'] ?>/-</div>
                <div class="rent-unit">per month</div>
            </div>
            <div class="button-container col-6">
                <a href="#" class="btn btn-primary">Book Now</a>
            </div>
        </div>
    </div>

    <div class="property-amenities">
        <div class="page-container">
            <h1>Amenities</h1>
            <div class="row justify-content-between">
                <div class="col-md-auto">
                    <h5>Building</h5>
                    <?php foreach ($details as $detail) {
                            if ($detail['type'] == 'Building') {
                    ?>
                        <div class="amenity-container">
                            <img src="img/amenities/<?php echo $detail['icon'] ?>.svg">
                            <span><?php echo $detail['name'] ?></span>
                        </div>
                    <?php } }?>
                </div>

                <div class="col-md-auto">
                    <h5>Common Area</h5>
                    <?php foreach ($details as $detail) {
                            if ($detail['type'] == 'Common Area') {
                    ?>
                        <div class="amenity-container">
                            <img src="img/amenities/<?php echo $detail['icon'] ?>.svg">
                            <span><?php echo $detail['name'] ?></span>
                        </div>
                    <?php } }?>
                </div>

                <div class="col-md-auto">
                    <h5>Bedroom</h5>
                    <?php foreach ($details as $detail) {
                            if ($detail['type'] == 'Bedroom') {
                    ?>
                        <div class="amenity-container">
                            <img src="img/amenities/<?php echo $detail['icon'] ?>.svg">
                            <span><?php echo $detail['name'] ?></span>
                        </div>
                    <?php } }?>
                </div>

                <div class="col-md-auto">
                    <h5>Washroom</h5>
                    <?php foreach ($details as $detail) {
                            if ($detail['type'] == 'Washroom') {
                    ?>
                        <div class="amenity-container">
                            <img src="img/amenities/<?php echo $detail['icon'] ?>.svg">
                            <span><?php echo $detail['name'] ?></span>
                        </div>
                    <?php } }?>
                </div>
            </div>
        </div>
    </div>

    <div class="property-about page-container">
        <h1>About the Property</h1>
        <p><?php echo $property['description'] ?></p>
    </div>

    <div class="property-rating">
        <div class="page-container">
            <h1>Property Rating</h1>
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6">
                    <div class="rating-criteria row">
                        <div class="col-6">
                            <i class="rating-criteria-icon fas fa-broom"></i>
                            <span class="rating-criteria-text">Cleanliness</span>
                        </div>
                        <div class="rating-criteria-star-container col-6" title= <?php echo $property['rating_clean'] ?> >
                            <?php for($i = 0; $i < round($property['rating_clean'], 0, PHP_ROUND_HALF_DOWN); $i++) { ?>
                                <i class="fas fa-star"></i>
                            <?php } ?>
                            <?php if ($property['rating_clean'] - (round($property['rating_clean'], 0, PHP_ROUND_HALF_DOWN )) >= 0.4) { ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="rating-criteria row">
                        <div class="col-6">
                            <i class="rating-criteria-icon fas fa-utensils"></i>
                            <span class="rating-criteria-text">Food Quality</span>
                        </div>
                        <div class="rating-criteria-star-container col-6" title= <?php echo $property['rating_food'] ?> >
                            <?php for($i = 0; $i < round($property['rating_food'], 0, PHP_ROUND_HALF_DOWN); $i++) { ?>
                                <i class="fas fa-star"></i>
                            <?php } ?>
                            <?php if ($property['rating_food'] - (round($property['rating_food'], 0, PHP_ROUND_HALF_DOWN )) >= 0.4) { ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="rating-criteria row">
                        <div class="col-6">
                            <i class="rating-criteria-icon fa fa-lock"></i>
                            <span class="rating-criteria-text">Safety</span>
                        </div>
                        <div class="rating-criteria-star-container col-6" title= <?php echo $property['rating_safety'] ?> >
                            <?php for($i = 0; $i < round($property['rating_safety'], 0, PHP_ROUND_HALF_DOWN); $i++) { ?>
                                <i class="fas fa-star"></i>
                            <?php } ?>
                            <?php if ($property['rating_safety'] - (round($property['rating_safety'], 0, PHP_ROUND_HALF_DOWN )) >= 0.4) { ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="rating-circle">
                    <?php
                        $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                        $avg_rating = round($total_rating, 1);
                    ?>
                        <div class="total-rating"> <?php echo $avg_rating ?> </div>
                        <div class="rating-circle-star-container">
                            <?php for($i = 0; $i < round($avg_rating, 0, PHP_ROUND_HALF_DOWN); $i++) { ?>
                                <i class="fas fa-star"></i>
                            <?php } ?>
                            <?php if ($avg_rating - (round($avg_rating, 0, PHP_ROUND_HALF_DOWN )) >= 0.4) { ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="property-testimonials page-container">
        <h1>What people say</h1>
        <div class="testimonial-block">
            <div class="testimonial-image-container">
                <img class="testimonial-img" src="img/man.png">
            </div>
            <div class="testimonial-text">
                <i class="fa fa-quote-left" aria-hidden="true"></i>
                <p>You just have to arrive at the place, it's fully furnished and stocked with all basic amenities and services and even your friends are welcome.</p>
            </div>
            <div class="testimonial-name">- Ashutosh Gowariker</div>
        </div>
        <div class="testimonial-block">
            <div class="testimonial-image-container">
                <img class="testimonial-img" src="img/man.png">
            </div>
            <div class="testimonial-text">
                <i class="fa fa-quote-left" aria-hidden="true"></i>
                <p>You just have to arrive at the place, it's fully furnished and stocked with all basic amenities and services and even your friends are welcome.</p>
            </div>
            <div class="testimonial-name">- Karan Johar</div>
        </div>
    </div>

    <?php
        include "includes/footer.php";
    ?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>

</html>
