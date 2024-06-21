<?php
include_once('db.php.inc');

try {
    $pdo = connectionDataBase();
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['carID'])) {
        $message = "Invalid request.";
        exit;
    }


    $carID = $_GET['carID'];
    if ($carID) {
        $carQuery = $pdo->prepare("
            SELECT * FROM cars c 
            WHERE c.id = :id
        ");
        $carQuery->bindValue(':id', $_GET['carID']);
        $carQuery->execute();


        $carDetails = $carQuery->fetch(PDO::FETCH_ASSOC);
        if (!$carDetails) {
            $message = "Car not found.";
            exit;
        }
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $status = $_POST['status'];
        $pickupLocation = $_POST['pickupName'];

        $updateCarQuery = $pdo->prepare("UPDATE cars c SET c.carStatus = :status ,c.locationID = :locationID WHERE c.id = :id");
        $updateCarQuery->bindParam(':id', $carID);
        $updateCarQuery->bindParam(':locationID', $_POST['pickupName']);
        $updateCarQuery->bindParam(':status', $_POST['status']);
        $updateCarQuery->execute();
        $rowCount = $updateCarQuery->rowCount();

        if ($rowCount > 0) {
            $message = "Car details updated successfully.";
        } else {
            $message = "Failed to update car details.";
        }
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Car Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
my_header();
?>

<main class="main-returnCarFromManager">
    <article>
        <div class="text_success">
            <?php
            if (isset($message)) {
                echo $message;
            } else {
                echo '';
            }
            ?>
        </div>

        <div class="filter-row">
            <label>Car ID :</label>
            <input type="text" value="<?php echo($_GET['carID']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car model :</label>
            <input type="text" value="<?php echo($carDetails['model']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car make :</label>
            <input type="text" value="<?php echo($carDetails['make']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car type :</label>
            <input type="text" value="<?php echo($carDetails['type']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Registration year :</label>
            <input type="text" value="<?php echo($carDetails['registrationYear']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Description :</label>
            <input type="text" value="<?php echo($carDetails['description']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Price per day :</label>
            <input type="text" value="<?php echo($carDetails['pricePerDay']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Capacity people :</label>
            <input type="text" value="<?php echo($carDetails['capacityPeople']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Capacity suitcases :</label>
            <input type="text" value="<?php echo($carDetails['capacitySuitcases']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Color :</label>
            <input type="text" value="<?php echo($carDetails['color']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Fuel type :</label>
            <input type="text" value="<?php echo($carDetails['fuelType']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Avg consumption :</label>
            <input type="text" value="<?php echo($carDetails['avgConsumption']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Horsepower :</label>
            <input type="text" value="<?php echo($carDetails['horsepower']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car Length :</label>
            <input type="text" value="<?php echo($carDetails['length']); ?>" disabled>
        </div>


        <div class="filter-row">
            <label>Car Condition Or Restrictions:</label>
            <input type="text" value="<?php echo($carDetails['conditionOrRestrictions']); ?>" disabled>
        </div>

        <div class="filter-row">
            <label>Car plate number :</label>
            <input type="text" value="<?php echo($carDetails['plateNumber']); ?>" disabled>
        </div>

        <form method="POST" action="">
            <div class="filter-row">
                <input type="hidden" name="carID" value="<?php echo($carDetails['id']); ?>">
                <label>Car status :
                    <select name="status" required>
                        <option value="1">
                            Available
                        </option>
                        <option value="4">Damaged
                        </option>
                        <option value="5">Repair
                        </option>
                    </select>
                </label>
            </div>

            <div class="filter-row">
                <label>Pick-up location :
                    <select name="pickupName" required>
                        <?php
                        $pickupNamesQuery = $pdo->prepare("SELECT  l.pickupName ,l.id FROM locations l");
                        $pickupNamesQuery->execute();
                        while ($row = $pickupNamesQuery->fetch()) {
                            $selected = ($row['id'] == $carDetails['locationID']) ? 'selected' : '';
                            echo '<option value="' . $row['id'] . '"' . $selected . '>' . $row['pickupName'] . '</option>';
                        }
                        ?>
                    </select>
                </label>
                <input type="submit" name="submit" value="Update" class="submit-button">
            </div>

        </form>
    </article>
</main>
<?php
my_footer();
?>
</body>
</html>
