<?php
include_once('db.php.inc');

try {
    $pdo = connectionDataBase();

    // Example query to fetch rented cars
    $sql = $pdo->prepare("SELECT
    c.id AS carID,
    c.make,
    c.type,
    c.model,
    r.rentStartDate AS pickupDate,
    r.rentEndDate AS returnDate,
    l.pickupName AS returnLocation
FROM
    cars c
JOIN
    rentals r ON c.id = r.carID
JOIN
    locations l ON r.returnLocation = l.id
WHERE
    c.carStatus = '2'
and CURDATE() > r.rentStartDate
");

    $sql->execute();
    $rentedCars = $sql->fetchAll(PDO::FETCH_ASSOC);


    // Handle car return action
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['carID'])) {
        $carID = $_POST['carID'];
        $sql = $pdo->prepare("UPDATE cars SET carStatus = '3' WHERE id = :carID AND carStatus = '2'");
        $sql->bindParam(":carID", $carID);
        $sql->execute();

        $message = "Car with ID $carID has been returned.";
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $message = 'Car ID not found.';
    }


} catch (Exception $e) {
    die($e->getMessage());
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Return Car</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
my_header();
?>

<main class="main-returnCar">
    <article>
        <div class="text_success">
            <?php
            if (isset($message)) {
                echo $message;
            }
            ?>
        </div>
        <table class="table-return-car">
            <caption>Cars to return</caption>
            <thead>
            <tr>
                <th>Car ID</th>
                <th>Car make</th>
                <th>Car type</th>
                <th>Car model</th>
                <th>Pick-up date</th>
                <th>Return date</th>
                <th>Return Location</th>
                <th>Action return</th>
            </tr>
            </thead>
            <tbody class="body-table-return-car">
            <?php foreach ($rentedCars as $rental): ?>
                <tr>
                    <td><?php echo($rental['carID']); ?></td>
                    <td><?php echo($rental['make']); ?></td>
                    <td><?php echo($rental['type']); ?></td>
                    <td><?php echo($rental['model']); ?></td>

                    <td><?php echo($rental['pickupDate']); ?></td>
                    <td><?php echo($rental['returnDate']); ?></td>
                    <td><?php echo($rental['returnLocation']); ?></td>
                    <td>
                        <form method="post" action="returnCar.php">
                            <input type="hidden" name="carID" value="<?php echo htmlspecialchars($rental['carID']); ?>">
                            <button type="submit" class="submit-button">
                                <img src="./carsImages/return_car_after_rent.png" alt="Submit Icon"> Return
                            </button>

                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </article>
</main>

<?php
my_footer();
?>
</body>
</html>
