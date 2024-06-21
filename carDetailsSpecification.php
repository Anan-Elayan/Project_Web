<?php

include_once('db.php.inc');
$conn = connectionDataBase();

if (isset($_GET["id"])) {

    $carID = $_GET["id"];
    $stm = 'SELECT * FROM cars WHERE id = :carID';
    $stmt = $conn->prepare($stm);
    $stmt->bindParam(':carID', $carID);
    $stmt->execute();
    $row = $stmt->fetch();

    $stmCarsPhotos = 'SELECT *  FROM carsphotos WHERE carID = :carID';
    $stmtCarPhoto = $conn->prepare($stmCarsPhotos);
    $stmtCarPhoto->bindParam(':carID', $row['id']);
    $stmtCarPhoto->execute();
    $carPhotos = $stmtCarPhoto->fetchAll();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Car Details</title>
</head>
<body>
<?php my_header(); ?>
<hr>
<main class="main-rent">
    <article>
        <section class="car-card">
            <div class="left-column">
                <?php foreach ($carPhotos as $photo): ?>
                    <figure class="car-image">
                        <img src="<?php echo $photo['photoPath']; ?>">
                    </figure>
                <?php endforeach; ?>
                <ul class="car-details">
                    <li class="carID"><span class="label_in_rent_car_step_two">Reference Number:</span> <span
                                class="value"><?php echo $row['id']; ?></span></li>
                    <li class="carType"><span class="label_in_rent_car_step_two">Type:</span> <span class="value"><?php echo $row['type']; ?></span></li>
                    <li class="carModel"><span class="label_in_rent_car_step_two">Model:</span> <span class="value"><?php echo $row['model']; ?></span></li>
                    <li class="carMake"><span class="label_in_rent_car_step_two">Make:</span> <span class="value"><?php echo $row['make']; ?></span></li>
                    <li class="carRegistrationYear"><span class="label_in_rent_car_step_two">Registration Year:</span> <span
                                class="value"><?php echo $row['registrationYear']; ?></span></li>
                    <li class="carColor"><span class="label_in_rent_car_step_two">Color:</span> <span class="value"><?php echo $row['color']; ?></span></li>
                    <li class="Description"><span class="label_in_rent_car_step_two">Description:</span> <span
                                class="value"><?php echo $row['description']; ?></span></li>
                    <li class="PriceperDay"><span class="label_in_rent_car_step_two">Price per Day:</span> <span
                                class="value"><?php echo $row['pricePerDay']; ?></span></li>
                    <li class="CapacityPeople"><span class="label_in_rent_car_step_two">Capacity People:</span> <span
                                class="value"><?php echo $row['capacityPeople']; ?></span></li>
                    <li class="CapacitySuitcases"> <span class="label_in_rent_car_step_two">Capacity Suitcases:</span> <span
                                class="value"><?php echo $row['capacitySuitcases']; ?></span></li>
                    <li class="FuelType"><span class="label_in_rent_car_step_two">Fuel Type:</span> <span class="value"><?php echo $row['fuelType']; ?></span>
                    </li>
                    <li class="AverageConsumption"><span class="label_in_rent_car_step_two">Average Consumption per 100KM:</span> <span
                                class="value"><?php echo $row['avgConsumption']; ?></span></li>
                    <li class="Horsepower"><span class="label_in_rent_car_step_two">Horsepower:</span> <span
                                class="value"><?php echo $row['horsepower']; ?></span></li>
                    <li class="Length"><span class="label_in_rent_car_step_two">Length:</span> <span class="value"><?php echo $row['length']; ?></span></li>
                    <li class="Width"><span class="label_in_rent_car_step_two">Width:</span> <span class="value"><?php echo $row['width']; ?></span></li>
                    <li class="GearType"><span class="label_in_rent_car_step_two">Gear Type:</span> <span class="value"><?php echo $row['gearType']; ?></span>
                    </li>
                    <li class="Restrictions"><span class="label_in_rent_car_step_two">Conditions or Restrictions:</span> <span
                                class="value"><?php echo ''; ?></span></li>
                </ul>
                <form action="rentCar_step1.php" method="get">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                    <div>
                        <button type="submit" name="rentCar" value="Rent a Car" class="submit-button">
                            <img src="./carsImages/cart.png" alt="Submit Icon"> Rent a Car
                        </button>
                    </div>
                </form>
            </div>
            <div class="right-column">
                <span class="label_in_rent_car_step_two">Description:</span>
                <p class="car-description">
                    <?php echo $row['description']; ?>
                </p>
            </div>
        </section>
    </article>
</main>

<?php my_footer(); ?>
</body>
</html>
