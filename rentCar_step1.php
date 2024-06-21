<?php
session_start();
include_once('db.php.inc');

// Function to check if the user is logged in
function is_logged_in()
{
    return isset($_SESSION['user']) && $_SESSION['user'];
}

// Redirect to login page if not logged in
if (!is_logged_in()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: rentCar_step2.php');
    exit();
}

try {
    $pdo = connectionDataBase();

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        // Fetch car details from the database using $id
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        $car = $stmt->fetch();
        if (!$car) {
            echo 'Car not found.';
            exit();
        }

        // Fetch available pickup locations that are not currently picked up
        $stmt = $pdo->prepare("
            SELECT * 
            FROM locations 
            
        ");
        $stmt->execute();
        $available_locations = $stmt->fetchAll();

        // Fetch all locations for the return location selection
        $stmt = $pdo->prepare("SELECT * FROM locations");
        $stmt->execute();
        $all_locations = $stmt->fetchAll();

    } else {
        echo 'Car ID is not set.';
        exit();
    }

} catch (Exception $e) {
    die($e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form submission
    $_SESSION['rental_data'] = $_POST;
    $_SESSION['car'] = $car;
    // Redirect to  next step
    header('Location: rentCar_step2.php?id=' . $id);
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Car</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php my_header(); ?>

<main class="main-rent-step1">
    <form method="post" action="">
        <fieldset>
            <legend class="legendAddCar"> Rent Car</legend>
            <ul class="car-details">
                <li>Car Type: <?php echo htmlspecialchars($car['type']); ?></li>
                <li>Car Description: <?php echo htmlspecialchars($car['description']); ?></li>
                <li>Car ID: <?php echo htmlspecialchars($car['id']); ?></li>
                <li>Price per day: <?php echo htmlspecialchars($car['pricePerDay']); ?></li>

                <?php
                $location = '';
                foreach ($available_locations as $location)
                    if ($location['id'] == $car['locationID']) {
                        $location = $location['city'] . ' - ' . $location['pickupName'];
                        break;
                    }
                ?>
                <li>Pickup location: <?php echo $location; ?></li>

            </ul>

            <div class="filter-row">
                <label>Pick-up Date</label>
                <input type="datetime-local" name="pickup_datetime" required>
                <span class="error-message">This field is required.</span>
            </div>

            <div class="filter-row">
                <label>Return Date</label>
                <input type="datetime-local" name="return_datetime" required>
                <span class="error-message">This field is required.</span>
            </div>

            <div class="div-special">
                <label style="margin-left: 10px">Special Requirements (optional)</label>
                <div class="filter-row">
                    <div class="filter-row">
                        <label>Return Location</label>
                        <select name="returnLocation" id="return_location">
                            <?php foreach ($all_locations as $location): ?>
                                <option value="<?php echo($location['id']); ?> " <?php echo $location['id'] == $car['locationID'] ? 'selected' : ' ' ?>>
                                    <?php echo($location['city'] . ' - ' . $location['pickupName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error-message">This field is required.</span>
                    </div>
                    <div class="filter-row">
                        <label>Baby Seat</label>
                        <input type="number" name="babySeat" max="<?php echo $car['capacityPeople'] ?>" min="1">
                        <span class="error-message">This field is required.</span>
                    </div>
                    <label><input type="checkbox" name="insurance" value="insurance"> Insurance</label>
                </div>
                <div class="div-insurance" >
                    <label class="lbl" for="whichCredit">Select which Credit:</label>
                    <input type="radio" name="whichCredit" value="1" required>My Old card
                    <input type="radio" name="whichCredit" value="2" required>New Card
                </div>
            </div>
            <button type="submit" class="submit-button">Continue</button>
        </fieldset>
    </form>

</main>
<?php my_footer(); ?>
</body>

