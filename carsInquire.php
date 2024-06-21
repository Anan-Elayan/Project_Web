<?php
include_once('db.php.inc');


try {
    $pdo = connectionDataBase();

// Initialize query parts
    $query = 'SELECT 
    c.id, 
    c.type,
    c.model,
    c.description,
    c.fuelType,
    c.carStatus, 
    r.rentStartDate,
    r.rentEndDate 
FROM cars c 
LEFT JOIN rentals r ON c.id = r.carID
WHERE 1=1 ';

    $parameters = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])) {
        if (!empty($_POST['pickUpLocation'])) {
            $query .= ' AND c.locationID = :pickUpLocation';
            $parameters[':pickUpLocation'] = $_POST['pickUpLocation'];
        }

        if (!empty($_POST['returnLocation'])) {
            $query .= ' AND c.locationID = :returnLocation';
            $parameters[':returnLocation'] = $_POST['returnLocation'];
        }

        if (!empty($_POST['carReturnedCertainDay'])) {
            $query .= ' AND DATE(r.rentEndDate) = :carReturnedCertainDay';
            $parameters[':carReturnedCertainDay'] = $_POST['carReturnedCertainDay'];

        }


        // or (r.rentStartDSate is null or r.rentEndDate)
        if (!empty($_POST['availableStartDate']) && !empty($_POST['availableEndDate'])) {
            $query .= ' AND (DATE(r.rentStartDate) > :availableEndDate OR DATE(r.rentEndDate) < :availableStartDate or r.rentStartDate is null or r.rentEndDate)';
            $parameters[':availableStartDate'] = $_POST['availableStartDate'];
            $parameters[':availableEndDate'] = $_POST['availableEndDate'];
//            var_dump($_POST['availableEndDate']);
//            die();
        }

        if ((!empty($_POST['availableStartDate']) && empty($_POST['availableEndDate']))
            || (empty($_POST['availableStartDate']) && !empty($_POST['availableEndDate']))) {
            $msg = "Two field is required when search from - to available car";
        }


        $carStatus = '';
        if (!empty($_POST['carStatusRepair'])) {
            $carStatus .= '5,';
        }

        if (!empty($_POST['carStatusDamage'])) {
            $carStatus .= '4';
        }

        $query .= ' AND c.carStatus IN (:carStatus)';
        $parameters[':carStatus'] = empty($carStatus) ? '1' : $carStatus;
    }else {

        $query .= ' AND c.carStatus = 1 AND (DATE(r.rentStartDate) > (CURDATE() + INTERVAL 7 day)  OR DATE(r.rentEndDate) < CURDATE() or r.rentStartDate is null or r.rentEndDate)';


    }


//    $query .= ' LIMIT 1';
    $stmt = $pdo->prepare($query);
    $stmt->execute($parameters);
    $results = $stmt->fetchAll();


    $queryPickupLocation = 'SELECT l.id , l.pickupName FROM locations l';
    $statement = $pdo->prepare($queryPickupLocation);
    $statement->execute();
    $result = $statement->fetchAll();


} catch (Exception $e) {
    die($e->getMessage());
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Car Inquiry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php my_header(); ?>

<main class="main-carInquire">
    <article>
        <form action="" method="POST" >
            <fieldset>
                <legend>Filter Cars</legend>
                <div class="text_success">

                    <?php
                    if (isset($msg)) {
                        echo $msg;
                    }

                    ?>
                </div>
                <div class="filter-row">
                    <label>Available from:</label>
                    <input type="date" name="availableStartDate">
                    <label>to:</label>
                    <input type="date" name="availableEndDate">
                </div>

                <div class="filter-row">
                    <label>Pick-up Location:</label>
                    <select name="pickUpLocation">
                        <option value=""></option>
                        <?php
                        foreach ($result as $row) {
                            echo '<option value="' . $row['id'] . '">' . $row["pickupName"] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="filter-row">
                    <label>Cars returned on:</label>
                    <input type="date" name="carReturnedCertainDay" placeholder="2024-02-03"></label>
                </div>

                <div class="filter-row">
                    <label>Return Location:</label>

                    <select name="returnLocation">
                        <option value=""></option>
                        <?php
                        foreach ($result as $row) {
                            echo '<option value="' . $row['id'] . '">' . $row["pickupName"] . '</option>';
                        }
                        ?>
                    </select>

                </div>

                <p>
                    <input type="checkbox" name="carStatusRepair" value="5" id="repair"
                    <label for="repair">All cars in repair</label>

                </p>

                <p>
                    <input type="checkbox" name="carStatusDamage" value="4" id="damage"
                    <label for="damage">All cars in Damage</label>
                </p>

                <button type="submit" name="filter" value="Filter" class="submit-button">
                    <img src="./carsImages/inquier_car.png" alt="Submit Icon"> Filter
                </button>
            </fieldset>
        </form>

        <table>
            <caption>Car Inquiry Results</caption>
            <thead>
            <tr>
                <th>Car ID</th>
                <th>Type</th>
                <th>Model</th>
                <th>Description</th>
                <th>Photo</th>
                <th>Fuel Type</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($results)): ?>
                <?php foreach ($results as $row) {
                    $fuelClass = ($row['fuelType']);
                    ?>
                    <tr class="<?php echo($fuelClass); ?>">
                        <td><?php echo($row['id']); ?></td>
                        <td><?php echo($row['type']); ?></td>
                        <td><?php echo($row['model']); ?></td>
                        <td><?php echo($row['description']); ?></td>
                        <td>
                            <?php
                            $queryr = 'SELECT c.photoPath  FROM carsphotos c WHERE carID = :carID LIMIT 1 ';
                            $stmt2 = $pdo->prepare($queryr);
                            $stmt2->bindParam(':carID', $row['id']);
                            $stmt2->execute();
                            $photo = $stmt2->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <img src="<?php echo($photo['photoPath']); ?>" alt="Car Photo" width="100">
                        </td>
                        <td><?php echo($row['fuelType']); ?></td>
                        <td><?php echo($row['carStatus']); ?></td>
                    </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No cars found based on selected filters.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </article>
</main>

<?php my_footer(); ?>
</body>
</html>