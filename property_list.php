<?php
    session_start();
    require "includes/database.php";

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
    $city_name = $_GET["city"];

    $sql_1 = "SELECT * FROM cities WHERE name = '$city_name'";
    $result_1 = mysqli_query($conn, $sql_1);
    if (!$result_1) {
        echo "Something went wrong!";
        return;
    }
    $city = mysqli_fetch_assoc($result_1);
    if ($city) {
        $city_id = $city['id'];


        $sql_2 = "SELECT * FROM properties WHERE city_id = $city_id";
        $result_2 = mysqli_query($conn, $sql_2);
        if (!$result_2) {
            echo "Something went wrong!";
            return;
        }
        $properties = mysqli_fetch_all($result_2, MYSQLI_ASSOC);


        $sql_3 = "SELECT *
            FROM interested_users_properties iup
            INNER JOIN properties p ON iup.property_id = p.id
            WHERE p.city_id = $city_id";
        $result_3 = mysqli_query($conn, $sql_3);
        if (!$result_3) {
            echo "Something went wrong!";
            return;
        }
        $interested_users_properties = mysqli_fetch_all($result_3, MYSQLI_ASSOC);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Best PG's in <?php echo $_GET['city']; ?> | PG Life</title>

    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet" />
    <link href="css/common.css" rel="stylesheet" />
    <link href="css/property_list.css" rel="stylesheet" />
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
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $_GET['city']; ?>
            </li>
        </ol>
    </nav>

    <div class="page-container">
        <div class="filter-bar row justify-content-around">
            <div class="col-auto" data-toggle="modal" data-target="#filter-modal">
                <img src="img/filter.png" alt="filter" />
                <span>Filter</span>
            </div>
            <div class="col-auto">
                <img src="img/desc.png" alt="sort-desc" />
                <span>Highest rent first</span>
            </div>
            <div class="col-auto">
                <img src="img/asc.png" alt="sort-asc" />
                <span>Lowest rent first</span>
            </div>
        </div>

        <?php
            if (!$city) {
            ?>
                <div class="no-property-container">
                    <p>Sorry! We do not have any PG listed in this city.</p>
                </div>
            <?php
                } else {
                foreach ($properties as $property) {
                    $property_images = glob("img/properties/".$property['id']."/*");
            ?>

                <div class="property-card propert-id-<?php echo $property['id'] ?> row">
                    <div class="image-container col-md-4">
                        <img src="<?php echo $property_images[0] ?>" />
                    </div>
                    <div class="content-container col-md-8">
                        <div class="row no-gutters justify-content-between">
                            <?php
                                $total_rating = ($property['rating_clean'] + $property['rating_food'] + $property['rating_safety']) / 3;
                                $avg_rating = round($total_rating, 1);
                            ?>
                            <div class="star-container" title= <?php echo $avg_rating ?> >
                                <?php for($i = 0; $i < round($avg_rating, 0, PHP_ROUND_HALF_DOWN); $i++) { ?>
                                    <i class="fas fa-star"></i>
                                <?php } ?>
                                <?php if ($avg_rating - (round($avg_rating, 0, PHP_ROUND_HALF_DOWN )) >= 0.4) { ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php } ?>
                            </div>
                            <div class="interested-container">
                                <i class="far fa-heart" onclick = "toggleLike(this)"></i>
                                <?php $count = 0;
                                    for($i = 0; $i < count($interested_users_properties); $i++){
                                        if ($interested_users_properties[$i]['property_id'] == $property['id'] ){
                                            $count++;
                                        }
                                    }
                                ?>
                                <div class="interested-text"><?php echo $count ?> interested</div>
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
                                <a href="property_detail.php?city=<?php echo $_GET['city']; ?>& name=<?php echo $property['name']; ?>& id=<?php echo $property['id']; ?>" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php }
            }
        ?>

    </div>

    <div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="filter-heading" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="filter-heading">Filters</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h5>Gender</h5>
                    <hr />
                    <div>
                        <button class="btn btn-outline-dark btn-active">
                            No Filter
                        </button>
                        <button class="btn btn-outline-dark">
                            <i class="fas fa-venus-mars"></i>Unisex
                        </button>
                        <button class="btn btn-outline-dark">
                            <i class="fas fa-mars"></i>Male
                        </button>
                        <button class="btn btn-outline-dark">
                            <i class="fas fa-venus"></i>Female
                        </button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-success">Okay</button>
                </div>
            </div>
        </div>
    </div>


    <?php
        include "includes/footer.php";
    ?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>

</html>
