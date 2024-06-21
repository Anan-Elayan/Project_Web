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
    l.pickupName AS returnLocation,
    co.name AS customerName
FROM
    cars c
JOIN
    rentals r ON c.id = r.carID
JOIN
    locations l ON r.returnLocation = l.id
JOIN customers co ON co.id = r.customerID
WHERE
    c.carStatus = '3'
");

    $sql->execute();
    $rentedCars = $sql->fetchAll(PDO::FETCH_ASSOC);


} catch (Exception $e) {
    die($e->getMessage());
}
?>


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

<main class="main-returnCarManager">


    <article>
        <div class="text_success">
            <?php
            if (isset($message)) {
                echo $message;
            }
            ?>
        </div>
        <table border="\0\">
            <thead>
            <tr>
                <th>Car ID</th>
                <th>Car make</th>
                <th>Car type</th>
                <th>Car model</th>
                <th>Pick-up date</th>
                <th>Return date</th>
                <th>Return Location</th>
                <th>Customer Name</th>
                <th>Action return</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rentedCars as $rentals): ?>
                <tr>
                    <td><?php echo($rentals['carID']); ?></td>
                    <td><?php echo($rentals['make']); ?></td>
                    <td><?php echo($rentals['type']); ?></td>
                    <td><?php echo($rentals['model']); ?></td>

                    <td><?php echo($rentals['pickupDate']); ?></td>
                    <td><?php echo($rentals['returnDate']); ?></td>
                    <td><?php echo($rentals['returnLocation']); ?></td>
                    <td><?php echo $rentals['customerName'] ?></td>

                    <td>
                        <form method="get" action="returnCarFromManegar.php">
                            <button type="submit" name="carID" value="<?php echo htmlspecialchars($rentals['carID']); ?>" class="submit-button">
                                <img src="./carsImages/returnCar.png" alt="Submit Icon">Return
                            </button>
                        </form>
                        <form method="get" action="updateCar.php">
                            <button type="submit" name="carID" value="<?php echo htmlspecialchars($rentals['carID']); ?>" class="submit-button">
                                <img src="./carsImages/returnCar.png" alt="Submit Icon">Update
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

