<!DOCTYPE html>
<?php
    session_start();
?>
<html lang="en">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Welcome | PG Life</title>

        <link href="css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://use.fontawesome.com/releases/v6.1.1/css/all.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet" />
        <link href="css/common.css" rel="stylesheet" />
        <link href="css/index.css" rel="stylesheet" />
    </head>

    <body>
        <?php
            include "includes/login-signup.php";
            include "includes/header.php";
        ?>

        <div class = "center-banner">
            <h2 class = 'header-text'>Happiness per Square Foot</h2>
            <form class = "row justify-content-center" method = "GET" action = "property_list.php">
                <input type = 'text' class = 'input-text' name = 'city' placeholder="Enter your city to search for PGs">
                <button type = "submit" class = 'logo-search'><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>

        <div class = 'center-city'>
            <h1 class = 'city-text'>Major City</h1>
            <div class = 'row-city'>
                <a href = "property_list.php?city=Delhi"><button type = "submit" class = 'city-circle'><img src = "img/delhi.png" class="img-city"></button></a>
                <a href = "property_list.php?city=Mumbai"><button type = "submit" class = 'city-circle'><img src = "img/mumbai.png" class="img-city"></button></a>
                <a href = "property_list.php?city=Bangalore"><button type = "submit" class = 'city-circle'><img src = "img/bangalore.png" class="img-city"></button></a>
                <a href = "property_list.php?city=Hyderabad"><button type = "submit" class = 'city-circle'><img src = "img/hyderabad.png" class="img-city"></button></a>
            </div>
        </div>

        <?php
            include "includes/footer.php";
        ?>

        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>

    </body>

</html>