<?php
include_once('db.php.inc');
include_once('car.php');
try {
    $pdo = connectionDataBase();

    $sql = $pdo->prepare("SELECT MAX(id) as max_id FROM cars");
    $sql->execute();
    $row = $sql->fetch();
    $lastId = $row['max_id'] ? $row['max_id'] + 1 : 1;
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
        $carDetails = $carQuery->fetch();
    }

    $queryGetPhotos = $pdo->prepare("SELECT cp.photoPath FROM carsphotos cp WHERE carID = :carID");
    $queryGetPhotos->bindValue(':carID', $carID);
    $queryGetPhotos->execute();
    $carPhotos = $queryGetPhotos->fetchAll();







    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $queryUpdateCate = "UPDATE cars SET 
                cars.model=:model, cars.make=:make, cars.type=:type,
                cars.registrationYear=:registrationYear, cars.description=:description, cars.pricePerDay=:pricePerDay,
                cars.capacityPeople=:capacityPeople, cars.capacitySuitcases=:capacitySuitcases,
                cars.color=:color,cars.fuelType=:fuelType,
                cars.avgConsumption	=:avgConsumption,
                cars.horsepower	=:horsepower, cars.length=:length, cars.width=:width, cars.gearType=:gearType,
                cars.conditionOrRestrictions=:conditionOrRestrictions, cars.plateNumber=:plateNumber
                ";

        $result = $pdo->prepare($queryUpdateCate);
        $result->bindValue(':model', $_POST['carModel']);
        $result->bindValue(':make', $_POST['make']);
        $result->bindValue(':type', $_POST['type']);
        $result->bindValue(':gearType', $_POST['gearType']);
        $result->bindValue(':registrationYear', $_POST['registrationYear']);
        $result->bindValue(':description', $_POST['description']);
        $result->bindValue(':pricePerDay', $_POST['pricePerDay']);
        $result->bindValue(':capacityPeople', $_POST['capacityOfPeople']);
        $result->bindValue(':capacitySuitcases', $_POST['capacityOfSuitcases']);
        $result->bindValue(':color', $_POST['color']);
        $result->bindValue(':fuelType', $_POST['fuelType']);
        $result->bindValue(':avgConsumption', $_POST['avgConsumption']);
        $result->bindValue(':horsepower', $_POST['horsepower']);
        $result->bindValue(':length', $_POST['length']);
        $result->bindValue(':width', $_POST['width']);
        $result->bindValue(':plateNumber', $_POST['plateNumber']);
        $result->bindValue(':conditionOrRestrictions', $_POST['conditionsOrRestrictions']);
        $result->bindValue(':locationID', $_POST['location']);
        $queryUpdateCate->execute();
        $message = "Car updated.";


        if (isset($_FILES['photos']) && is_array($_FILES['photos']['name'])) {
            $numFiles = count($_FILES['photos']['name']);
            if ($numFiles >= 3) {
                $targetDir = "carsImages/";
                $uploadedFiles = [];
                for ($i = 0; $i < $numFiles; $i++) {
                    $fileName = $_FILES['photos']['name'][$i];
                    $fileTmpName = $_FILES['photos']['tmp_name'][$i];
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $allowedExtensions = ['jpeg', 'png', 'jpg'];
                    if (in_array($fileExtension, $allowedExtensions)) {
                        $newFileName = "car{$lastId}img{$i}.{$fileExtension}";
                        $targetFilePath = $targetDir . $newFileName;
                        if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                            $uploadedFiles[] = $targetFilePath;
                        } else {
                            echo "Error uploading file {$fileName}.<br>";
                        }
                    } else {
                        echo "File type not allowed for {$fileName}.<br>";
                    }
                }

                if (count($uploadedFiles) >= 3) {
                    if ($result->execute()) {
                        $newCarId = $pdo->lastInsertId();
                        foreach ($uploadedFiles as $uploadedFile) {
                            $queryInsertImages = 'INSERT INTO carsphotos (carID, photoPath) VALUES (:carID, :photoPath)';
                            $stmtInsertImages = $pdo->prepare($queryInsertImages);
                            $stmtInsertImages->bindValue(':carID', $newCarId);
                            $stmtInsertImages->bindValue(':photoPath', $uploadedFile);
                            $stmtInsertImages->execute();
                        }
                        $message = "Car added successfully with ID: $newCarId";
                    } else {
                        echo "Error adding car.<br>";
                    }
                } else {
                    echo "Please upload at least three valid image files.<br>";
                }
            } else {
                echo "Please upload at least three files.<br>";
            }
        } else {
            echo "No files uploaded or invalid file data.<br>";
        }


    }


} catch (Exception $e) {
    die($e->getMessage());
}


?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Care</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php my_header(); ?>


<main class="main-addNewCar">
    <article>
        <div class="text_success">
            <?php
            if (isset($message)) {
                echo $message;
            }

            ?>
        </div>
        <form method="POST" action=" " enctype="multipart/form-data" class="formAddCar">
            <fieldset class="fieldSetAddCar">
                <legend class="legendAddCar"> Add New Car</legend>
                <div class="filter-row">
                    <label>Car Model</label>
                    <input type="text" name="carModel" value=<?php echo $carDetails['model'] ?> required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Car Make</label>
                    <select name="make" required>
                        <?php
                        $carMake = $carDetails['make'];
                        $carMakes = ['BMW', 'VW', 'Volvo', 'Mercedes', 'Audi', 'Honda', 'Seat', 'RangeRover', 'Skoda'];
                        foreach ($carMakes as $make) {
                            $selected = ($carMake == $make) ? 'selected' : ''; // Check if current make is the selected make
                            echo '<option value="' . htmlspecialchars($make) . '" ' . $selected . '>' . htmlspecialchars($make) . '</option>';
                        }
                        ?>
                    </select>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Car Type</label>
                    <select name="type" required>
                        <?php
                        $carType = $carDetails['type'];
                        $carTypes = ['Van', 'Mini-Van', 'Sport', 'Sedan', 'SUV'];
                        foreach ($carTypes as $type) {
                            $selected = ($carType == $type) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($type) . '" ' . $selected . '>' . htmlspecialchars($type) . '</option>';
                        }
                        ?>
                    </select>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Gear Type</label>
                    <input type="radio" name="gearType"
                           value="Manual" <?php if ($carDetails['gearType'] == 'Manual') echo 'checked'; ?> required>Manual
                    <input type="radio" name="gearType"
                           value="Automatic" <?php if ($carDetails['gearType'] == 'Automatic') echo 'checked'; ?>
                           required>Automatic
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Registration Year</label>
                    <input type="number" name="registrationYear" min="2000" max="5000" placeholder="2024"
                           value=<?php echo $carDetails['registrationYear'] ?> required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Price per Day</label>
                    <input type="number" name="pricePerDay" value=<?php echo $carDetails['pricePerDay'] ?> required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Capacity of People</label>
                    <input type="number" name="capacityOfPeople"
                           value=<?php echo $carDetails['capacityPeople'] ?> required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Capacity of Suitcases</label>
                    <input type="number" name="capacityOfSuitcases"
                           value="<?php echo $carDetails['capacitySuitcases'] ?>" required>
                    <span class="error-message">This field is required.</span>
                </div>

                <div class="filter-row">
                    <label>Color</label>
                    <input type="text" name="color" value="<?php echo $carDetails['color'] ?>" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Fuel Type</label><br>

                    <select name="fuelType" required>
                        <?php
                        $carFuelType = $carDetails['fuelType'];
                        $carFuelType = ['Petrol', 'Diesel', 'Electric', 'Hybrid'];
                        foreach ($carFuelType as $type) {
                            $selected = ($carType == $type) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($type) . '" ' . $selected . '>' . htmlspecialchars($type) . '</option>';
                        }
                        ?>
                    </select>
                    <span class="error-message">This field is required.</span>

                </div>
                <div class="filter-row">
                    <label>Average Consumption per 100 KM</label>
                    <input type="number" name="avgConsumption" value="<?php echo $carDetails['avgConsumption'] ?>"
                           required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Horsepower</label>
                    <input type="number" name="horsepower" value="<?php echo $carDetails['horsepower'] ?>" required>
                    <span class="error-message">This field is required.</span>

                </div>
                <div class="filter-row">
                    <label>Length (in meters)</label>
                    <input type="number" name="length" step="0.01" value="<?php echo $carDetails['length'] ?>" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Width (in meters)</label>
                    <input type="number" name="width" step="0.01" value="<?php echo $carDetails['width'] ?>" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Plate Number</label>
                    <input type="text" name="plateNumber" value="<?php echo $carDetails['plateNumber'] ?>" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Location</label>
                    <select name="location" required>
                        <?php
                        $query = 'SELECT l.id , l.pickupName   FROM locations l';
                        $statement = $pdo->prepare($query);
                        if ($statement->execute()) {
                            $result = $statement->fetchAll();
                            foreach ($result as $row) {
                                echo '<option value="' . $row['id'] . '">' . $row["pickupName"] . '</option>';
                            }
                        } else {
                            echo '<option value="">No locations available</option>';
                        }
                        ?>
                    </select>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Description</label>
                    <textarea name="description" rows="4"
                              required><?php echo($carDetails['description']); ?></textarea>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Any Conditions or Restrictions</label>
                    <textarea name="conditionsOrRestrictions"
                              rows="4"><?php echo($carDetails['conditionOrRestrictions']); ?></textarea>
                    <span class="error-message">This field is required.</span>
                </div>


                <div class="filter-row">
                    <label>Photos (at least three)</label>
                    <input type="file" name="photos[]" accept="image/jpeg,image/png" multiple required>
                    <span class="error-message">This field is required.</span>
                </div>


                <div class="filter-row">
                    <button type="submit" name="addCar" value="Add Car" class="submit-button">
                        <img src="./carsImages/update.png" alt="Submit Icon"> Update Car
                    </button>
                    <span class="error-message">This field is required.</span>
                </div>
            </fieldset>
        </form>
    </article>
</main>


<?php my_footer(); ?>
</body>
</html>
