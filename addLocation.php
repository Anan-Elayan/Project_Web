<?php
include_once('db.php.inc');
include_once('locations.php');
try {
    $pdo = connectionDataBase();
// Assuming you have form data submitted via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Create a new Locations object with data from the form
        $newLocation = new Locations(
            $_POST['pickupName'],
            $_POST['city'],
            $_POST['telephone'],
            $_POST['street'],
            $_POST['country'],
            $_POST['propertyNumber'],
            $_POST['postalCode']
        );

        // Prepare SQL statement
        $query = 'INSERT INTO locations (pickupName, city, telephone, street, country, propertyNumber, postalCode) 
                    VALUES (:pickupName, :city, :telephone, :street, :country, :propertyNumber, :postalCode)';

        $stmt = $pdo->prepare($query);

        // Bind parameters
        $stmt->bindValue(':pickupName', $newLocation->getPickupName());
        $stmt->bindValue(':city', $newLocation->getCity());
        $stmt->bindValue(':telephone', $newLocation->getTelephone());
        $stmt->bindValue(':street', $newLocation->getStreet());
        $stmt->bindValue(':country', $newLocation->getCountry());
        $stmt->bindValue(':propertyNumber', $newLocation->getPropertyNumber());
        $stmt->bindValue(':postalCode', $newLocation->getPostalCode());

        // Execute the statement
        $stmt->execute();

        $message = "Location added successfully.";
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
    <title>Add new Location</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include_once('db.php.inc');
include_once('car.php');

my_header();
?>

<main class="main-addNewLocation">
    <article>
        <form method="post" action="addLocation.php">
            <fieldset>
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                    <?php if ($message): ?>
                        <h2><?php echo htmlspecialchars($message); ?></h2>
                    <?php endif; ?>
                <?php endif; ?>
                <legend>Add new Location</legend>

                <div class="filter-row">
                    <label for="pickupName">Name</label>
                    <input type="text" id="pickupName" name="pickupName" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label for="propertyNumber">Address</label>
                    <input type="number" id="propertyNumber" placeholder="Property number" name="propertyNumber"
                           required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <input type="text" placeholder="Street name" name="street" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <input type="text" placeholder="City" name="city" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <input type="number" placeholder="Postal code" name="postalCode" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <input type="text" placeholder="Country" name="country" required>
                    <span class="error-message">This field is required.</span>
                </div>

                <div class="filter-row">
                    <label for="telephone">Telephone Number</label>
                    <input type="text" id="telephone" placeholder="0591122334" name="telephone" required>
                    <span class="error-message">This field is required.</span>
                </div>


                <button type="submit" name="add" value="Add Location" class="submit-button">
                    <img src="./carsImages/add-location.png" alt="Submit Icon"> Add Location
                </button>
            </fieldset>
        </form>
    </article>
</main>

<?php
my_footer();
?>
</body>
</html>