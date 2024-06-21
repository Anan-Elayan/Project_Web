<?php
include_once("db.php.inc");
$pdo = connectionDataBase();

try {


    $custmer  = 'SELECT * FROM customers where userID = :userID ';
    $stmtment = $pdo->prepare($custmer);
    $stmtment->bindParam(':userID', $_SESSION['user']['id']);
    $stmtment->execute();
    $customerObj = $stmtment->fetch();


    $sql = "SELECT 
        i.id AS invoiceID, 
        i.invoiceDate, 
        c.type AS carType, 
        c.model AS carModel, 
        r.rentStartDate AS pickupDate, 
        r.returnLocation AS returnLocation,
        r.rentEndDate AS returnDate,
        rl.pickupName AS pickupName,
        rll.pickupName AS returnLocationName
    FROM 
        invoices i
    JOIN 
        rentals r ON i.rentalID = r.id
    JOIN 
        cars c ON r.carID = c.id
    JOIN 
        locations rl ON r.returnLocation = rl.id 
    
    JOIN  locations rll ON c.locationID = rll.id
    WHERE r.customerID = :customerID
    ORDER BY 
        r.rentStartDate DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':customerID', $customerObj['id']);
    $stmt->execute();
    $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error fetching rentals: " . $e->getMessage();
    $rentals = [];
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rented Cars</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<?php
my_header();
?>
<main class="main-viewRentedCars">
    <article>
        <div class="text_success">
            <?php
            if (isset($_SESSION['MSG_RENT_SUCCESS'])) {
                echo $_SESSION['MSG_RENT_SUCCESS'];
            }

            ?>
        </div>

        <div class="divHighlightPost">
            <label class="lblHighlight">Post</label>
        </div>

        <br>
        <div class="divHighlightCurrent">
            <label class="lblHighlight">Current</label>
        </div>

        <br>
        <div class="divHighlightFuture">
            <label class="lblHighlight">Future</label>
        </div>
        <br>

        <table>
            <caption>My cars</caption>
            <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Invoice Date</th>
                <th>Car Type</th>
                <th>Car Model</th>
                <th>Pick-up Date</th>
                <th>Pick-up Location</th>
                <th>Return Date</th>
                <th>Return Location</th>
                <!--                <th>Action</th>-->
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rentals as $rental): ?>
                <?php
                $invoiceDate = date('Y-m-d H:i:s', strtotime($rental['invoiceDate']));
                $pickupDate = date('Y-m-d H:i:s', strtotime($rental['pickupDate']));
                $returnDate = date('Y-m-d H:i:s', strtotime($rental['returnDate']));
                $currentDate = date('Y-m-d H:i:s');

                // Determine the rental status
                if ($pickupDate > $currentDate) {
                    $statusClass = 'future';
                } elseif ($returnDate >= $currentDate && $pickupDate <= $currentDate) {
                    $statusClass = 'current';
                } elseif ($returnDate < $currentDate) {
                    $statusClass = 'past';
                }

                ?>
                <tr class="<?php echo $statusClass; ?>">
                    <td><?php echo($rental['invoiceID']); ?></td>
                    <td><?php echo($invoiceDate); ?></td>
                    <td><?php echo($rental['carType']); ?></td>
                    <td><?php echo($rental['carModel']); ?></td>
                    <td><?php echo($pickupDate); ?></td>
                    <td><?php echo($rental['pickupName']); ?></td>
                    <td><?php echo($returnDate); ?></td>
                    <td><?php echo($rental['returnLocationName']); ?></td>
                    <!--                    <td>-->
                    <!--                        --><?php //if ($statusClass == 'current'): ?>
                    <!--                            <a href="return_car.php?invoiceID=-->
                    <?php //echo htmlspecialchars($rental['invoiceID']); ?><!--">Return Car</a>-->
                    <!--                        --><?php //endif; ?>
                    <!--                    </td>-->
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