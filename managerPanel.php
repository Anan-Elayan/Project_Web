<?php
include_once('db.php.inc');
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">

    <title>Document</title>
</head>
<body>
<?php
my_header();
?>
<main class="main-ManagerPanel">
    <article>
        <div class="card-container">
            <div id="containerAddNewCar" class="card">
                <img src="carsImages/add_car.png" alt="Add Car">
                <a href="addCar.php">Add new Car</a>
            </div>
            <div id="containerAddLocation" class="card">
                <img src="carsImages/location.png" alt="Add Location">
                <a href="addLocation.php">Add new Location</a>
            </div>
        </div>
        <div class="card-container">
            <div id="containerReturnCar" class="card">
                <img src="carsImages/returnCar.png" alt="Return Car">
                <a href="returnCarManegar.php">Return a Car</a>
            </div>
            <div id="containerCarInquire" class="card">
                <img src="carsImages/inquier_car.png" alt="Car Inquire">
                <a href="carsInquire.php">Cars Inquire</a>
            </div>
        </div>
    </article>
</main>

<?php
my_footer();
?>
</body>
</html>
