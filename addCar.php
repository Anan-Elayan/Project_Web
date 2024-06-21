<?php
include_once('db.php.inc');
include_once('car.php');

try {
    $pdo = connectionDataBase();

    // Get the maximum ID from the cars table
    $sql = $pdo->prepare("SELECT MAX(id) as max_id FROM cars");
    $sql->execute();
    $row = $sql->fetch();
    $lastId = $row['max_id'] ? $row['max_id'] + 1 : 1;

    if (isset($_POST['addCar'])) {
        // Prepare the SQL statement for car insertion
        $query = "INSERT INTO cars (model, make, type, gearType, registrationYear, description,
                                    pricePerDay, capacityPeople, capacitySuitcases, color, fuelType,
                                    avgConsumption, horsepower, length, width, plateNumber,
                                    carStatus, conditionOrRestrictions, locationID)
                  VALUES (:model, :make, :type, :gearType, :registrationYear,
                          :description, :pricePerDay, :capacityPeople, :capacitySuitcases,
                          :color, :fuelType, :avgConsumption, :horsepower, :length,
                          :width,  :plateNumber, 1, :conditionOrRestrictions, :locationID)";

        // Prepare the SQL statement
        $result = $pdo->prepare($query);

        // Bind form data to parameters
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
        $result->bindValue(':locationID', $_POST['location']  );


        // Handle file uploads
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
                        $message =  "Car added successfully with ID: $newCarId";
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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car</title>
    <link rel="stylesheet" href="style.css">
    <!-- Optional: Include any additional stylesheets or scripts -->
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
                    <input type="text" name="carModel" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Car Make</label>
                    <select name="make" required>
                        <option value="BMW">BMW</option>
                        <option value="VW">VW</option>
                        <option value="Volvo">Volvo</option>
                        <option value="Mercedes">Mercedes</option>
                        <option value="Audi">Audi</option>
                        <option value="Honda">Honda</option>
                        <option value="Seat">Seat</option>
                        <option value="RangeRover">Range Rover</option>
                        <option value="Skoda">Skoda</option>
                    </select>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Car Type</label>
                    <select name="type" required>
                        <option value="Van">Van</option>
                        <option value="Mini-Van">Mini-Van</option>
                        <option value="Sport">Sport</option>
                        <option value="Sedan">Sedan</option>
                        <option value="SUV">SUV</option>
                    </select>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Gear Type</label>
                    <input type="radio" name="gearType" value="Manual" required>Manual
                    <input type="radio" name="gearType" value="Automatic" required>Automatic
                    <span class="error-message">This field is required.</span>
                </div>

                <div class="filter-row">
                    <label>Registration Year</label>
                    <input type="number" name="registrationYear" min="2000" max="5000" placeholder="2024" required>
                    <span class="error-message">This field is required.</span>
                </div>

                <div class="filter-row">
                    <label>Price per Day</label>
                    <input type="number" name="pricePerDay" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Capacity of People</label>
                    <input type="number" name="capacityOfPeople" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Capacity of Suitcases</label>
                    <input type="number" name="capacityOfSuitcases" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Color</label>
                    <input type="text" name="color" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Fuel Type</label><br>
                    <select name="fuelType" required>
                        <option value="petrol">Petrol</option>
                        <option value="diesel">Diesel</option>
                        <option value="electric">Electric</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                    <span class="error-message">This field is required.</span>

                </div>
                <div class="filter-row">
                    <label>Average Consumption per 100 KM</label>
                    <input type="number" name="avgConsumption" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Horsepower</label>
                    <input type="number" name="horsepower" required>
                    <span class="error-message">This field is required.</span>

                </div>
                <div class="filter-row">
                    <label>Length (in meters)</label>
                    <input type="number" name="length" step="0.01" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>Width (in meters)</label>
                    <input type="number" name="width" step="0.01" required>
                    <span class="error-message">This field is required.</span>
                </div>

                <div class="filter-row">
                    <label>Plate Number</label>
                    <input type="text" name="plateNumber" required>
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
                                echo '<option value="' .$row['id']. '">' . $row["pickupName"]. '</option>';
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
                    <textarea name="description" rows="4" required></textarea>
                    <span class="error-message">This field is required.</span>
                </div>

                <div class="filter-row">
                    <label>Any Conditions or Restrictions</label>
                    <textarea name="conditionsOrRestrictions" rows="4"></textarea>
                    <span class="error-message">This field is required.</span>
                </div>

                <div class="filter-row">
                    <label>Photos (at least three)</label>
                    <input type="file" name="photos[]" accept="image/jpeg,image/png" multiple required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <button type="submit" name="addCar" value="Add Car" class="submit-button">
                        <img src="./carsImages/add-car.png" alt="Submit Icon"> Add Car
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

