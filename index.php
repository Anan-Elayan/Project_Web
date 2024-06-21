<?php
include('db.php.inc');
include('car.php');

// Connect to the database
$conn = connectionDataBase();

// Initialize default query
$query = "
    SELECT c.*, l.pickupName 
    FROM cars c
        LEFT JOIN rentals r ON c.id = r.carID
        INNER JOIN locations l ON l.id = c.locationID WHERE c.carStatus=1
";

$params = [];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['availableStartDate']) && !empty($_POST['availableEndDate'])) {
        $query .= ' AND (DATE(r.rentStartDate) > :availableEndDate OR DATE(r.rentEndDate) < :availableStartDate or r.rentStartDate is null or r.rentEndDate)';
        $parameters[':availableStartDate'] = $_POST['availableStartDate'];
        $parameters[':availableEndDate'] = $_POST['availableEndDate'];
    } else {
        $query .= ' AND c.carStatus = 1 AND (DATE(r.rentStartDate) > (CURDATE() + INTERVAL 3 day)  OR DATE(r.rentEndDate) < CURDATE() or r.rentStartDate is null or r.rentEndDate)';
    }

    if ((!empty($_POST['availableStartDate']) && empty($_POST['availableEndDate']))
        || (empty($_POST['availableStartDate']) && !empty($_POST['availableEndDate']))) {
        $msg = "Two field is required when search from - to available car";
    }

    if (!empty($_POST['carType'])) {
        $query .= " AND c.type = :carType ";
        $params[':carType'] = $_POST['carType'];
        setcookie('carType', $_POST['carType']);
    } else {
        $query .= " AND c.type = 'sedan'";
    }

    if (!empty($_POST['minPriceRange'])) {
        $query .= " AND c.pricePerDay >= :minPrice";
        $params[':minPrice'] = (int)$_POST['minPriceRange'];
        setcookie('minPriceRange', $_POST['minPriceRange']);
    } else {
        $query .= " AND c.pricePerDay >=200 ";
    }

    if (!empty($_POST['maxPriceRange'])) {
        $query .= " AND c.pricePerDay <= :maxPrice";
        $params[':maxPrice'] = (int)$_POST['maxPriceRange'];
        setcookie('maxPriceRange', $_POST['maxPriceRange']);
    } else {
        $query .= " AND c.pricePerDay <=1000";
    }

    if (!empty($_POST['pickUpLocation'])) {
        $query .= " AND l.pickupName LIKE :pickUpLocation";
        $params[':pickUpLocation'] = '%' . $_POST['pickUpLocation'] . '%';
        setcookie('pickUpLocation', $_POST['pickUpLocation']);
    } else {
        $query .= " AND l.pickupName = 'Birzeit' ";
    }

    if (!empty($_POST['selectedCars'])) {
        $placeholders = [];
        $params = [];

        foreach ($_POST['selectedCars'] as $index => $car) {
            $key = ":carID" . $index;
            $placeholders[] = $key;
            $params[$key] = (int)$car;
        }

        $inClause = '';
        foreach ($placeholders as $placeholder) {
            if ($inClause !== '') {
                $inClause .= ', ';
            }
            $inClause .= $placeholder;
        }

        $query .= " AND c.id IN (" . $inClause . ")";
    }
}

if (!empty($_GET['sortBy'])) {
    if ($_GET['sortBy'] == 'type') {
        $query .= " ORDER BY c.type ASC";
    } elseif ($_GET['sortBy'] == 'fuel') {
        $query .= " ORDER BY c.fuelType ASC";
    } elseif ($_GET['sortBy'] == 'price') {
        $query .= " ORDER BY c.pricePerDay ASC";
    }
}

$stmt = $conn->prepare($query);
$stmt->execute($params);

$cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Home Page</title>
</head>

<body>
<?php
my_header();
?>
<main class="main-Home">
    <div class="filter-section">
        <form method="post" action="" id="formFilter">
            <fieldset id="fieldSetFilter">
                <legend id="legendFilter">Filter Cars</legend>
                <div class="filter-row">
                    <label for="startRental">Rental period from</label>
                    <input name="startRental" type="date"
                           value="<?php echo isset($_COOKIE['startRental']) ? $_COOKIE['startRental'] : ''; ?>">
                    <label for="endRental">to</label>
                    <input name="endRental" type="date"
                           value="<?php echo isset($_COOKIE['endRental']) ? $_COOKIE['endRental'] : ''; ?>">
                </div>
                <div class="filter-row">
                    <label for="carType">Car Type</label>
                    <select name="carType" id="carType">
                        <option value="" selected>Select Category</option>
                        <option value="sedan" <?php echo isset($_COOKIE['carType']) && $_COOKIE['carType'] == 'sedan' ? 'selected' : ''; ?>>
                            Sedan
                        </option>
                        <option value="suv" <?php echo isset($_COOKIE['carType']) && $_COOKIE['carType'] == 'suv' ? 'selected' : ''; ?>>
                            SUV
                        </option>
                        <option value="hatchback" <?php echo isset($_COOKIE['carType']) && $_COOKIE['carType'] == 'hatchback' ? 'selected' : ''; ?>>
                            Hatchback
                        </option>
                        <option value="convertible" <?php echo isset($_COOKIE['carType']) && $_COOKIE['carType'] == 'convertible' ? 'selected' : ''; ?>>
                            Convertible
                        </option>
                        <option value="truck" <?php echo isset($_COOKIE['carType']) && $_COOKIE['carType'] == 'truck' ? 'selected' : ''; ?>>
                            Truck
                        </option>
                        <option value="sport" <?php echo isset($_COOKIE['carType']) && $_COOKIE['carType'] == 'sport' ? 'selected' : ''; ?>>
                            Sport
                        </option>
                    </select>
                </div>
                <div class="filter-row">
                    <label for="pickUpLocation">Pick-up Location</label>
                    <input name="pickUpLocation" type="text" placeholder="Birzeit"
                           value="<?php echo isset($_COOKIE['pickUpLocation']) ? $_COOKIE['pickUpLocation'] : ''; ?>">
                </div>
                <div class="filter-row">
                    <label for="minPriceRange">Price Range</label>
                    <input name="minPriceRange" type="number" placeholder="min"
                           value="<?php echo isset($_COOKIE['minPriceRange']) ? $_COOKIE['minPriceRange'] : ''; ?>">
                    <label for="maxPriceRange">to</label>
                    <input name="maxPriceRange" type="number" placeholder="max"
                           value="<?php echo isset($_COOKIE['maxPriceRange']) ? $_COOKIE['maxPriceRange'] : ''; ?>">
                </div>
                <div>
                    <button type="submit" name="filter" value="Filter" class="submit-button">
                        <img src="./carsImages/inquier_car.png" alt="Submit Icon"> Filter
                    </button>
                </div>
            </fieldset>
        </form>
    </div>

    <div class="cars-list">
        <form method="post" action="">
            <fieldset class="fieldSetAddCar">
                <legend>All Cars</legend>
                <table>
                    <caption>table search result</caption>
                    <thead>
                    <tr>
                        <th><input type="submit" name="selectAll" value="Display Selected cars"></th>
                        <th><a href="?sortBy=price">Price Per Day</a></th>
                        <th><a href="?sortBy=type">Car Type</a></th>
                        <th><a href="?sortBy=fuel">Fuel Type</a></th>
                        <th>Car Photo</th>
                        <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['isManager']) && $_SESSION['user']['isManager'] != 1): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cars as $car) {
                        $fuelClass = strtolower($car['fuelType']);
                        ?>
                        <tr class="<?php echo($fuelClass); ?>">
                            <td><input type="checkbox" name="selectedCars[]" value="<?php echo $car['id']; ?>"></td>
                            <td><?php echo($car['pricePerDay']); ?></td>
                            <td><?php echo($car['type']); ?></td>
                            <td><?php echo($car['fuelType']); ?></td>
                            <td>
                                <figure style="margin: 0">
                                    <?php
                                    $queryr = 'SELECT c.photoPath  FROM carsphotos c WHERE carID = :carID LIMIT 1 ';
                                    $stmt2 = $conn->prepare($queryr);
                                    $stmt2->bindParam(':carID', $car['id']);
                                    $stmt2->execute();
                                    $photo = $stmt2->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <img src="<?php echo($photo['photoPath']); ?>" alt="Car Photo" width="130">
                                    <figcaption><?php echo $car['model']; ?></figcaption>
                                </figure>
                            </td>
                            <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['isManager']) && $_SESSION['user']['isManager'] != 1): ?>
                                <td>
                                    <a href="carDetailsSpecification.php?id=<?php echo($car['id']); ?>">Rent Now</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <br>
            </fieldset>
        </form>
    </div>
</main>
<?php
my_footer();
?>
</body>
</html>
