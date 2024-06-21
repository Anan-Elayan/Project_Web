<?php
session_start();
include_once('db.php.inc');
$pdo = connectionDataBase();
if (!isset($_SESSION['rental_data'])) {
    header('Location: rent.php');
    exit();
}
$rental_data = $_SESSION['rental_data'];

function calculate_total_amount()
{
    $base_amount = $_SESSION['car']['pricePerDay'];
    if (isset($_SESSION['rental_data']['returnLocation'])) {
        $base_amount += 50;
    }
    if (isset($_SESSION['rental_data']['babySeat'])) {
        $base_amount += (20 * (int)$_SESSION['rental_data']['babySeat']);
    }
    if (isset($_SESSION['rental_data']['insurance'])) {
        $base_amount += 30;
    }
    return $base_amount;
}

$queryCustomer = 'SELECT * FROM customers c WHERE c.userID = :id';
$stmCustomer = $pdo->prepare($queryCustomer);
$stmCustomer->bindParam(':id', $_SESSION['user']['id']);
$stmCustomer->execute();
$customer = $stmCustomer->fetch();



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['confirm_name'] == $customer['name'] && $_POST['confirm_date'] == date('Y-m-d')) {

        $paymentId = $customer['paymentID'];
        if ($_SESSION['rental_data']['whichCredit'] == 2) {
            $quereyInsertNewPayment = 'INSERT INTO payments (creditCardNumber,creditCardExpiry,creditCardName,creditCardType,bankIssued) 
                                    values (:creditCardNumber, :creditCardExpiry, :creditCardName, :creditCardType, :bankIssued)';
            $quereyInsertNewPayment = $pdo->prepare($quereyInsertNewPayment);
            $quereyInsertNewPayment->bindParam(':creditCardNumber', $_POST['cc_number']);
            $quereyInsertNewPayment->bindParam(':creditCardExpiry', $_POST['cc_expiration']);
            $quereyInsertNewPayment->bindParam(':creditCardName', $_POST['cc_holder']);
            $quereyInsertNewPayment->bindParam(':creditCardType', $_POST['cc_type']);
            $quereyInsertNewPayment->bindParam(':bankIssued', $_POST['cc_bank']);
            $quereyInsertNewPayment->execute();
            $paymentId = $pdo->lastInsertId();
        }
        $newRent = 'INSERT INTO rentals (carID ,customerID ,rentStartDate,rentEndDate ,babySeats,insurance,returnLocation ) 
                VALUES (:carID ,:customerID,:rentStartDate,:rentEndDate, :babySeats,:insurance,:returnLocation)';
        $newRent = $pdo->prepare($newRent);
        $newRent->bindParam(':carID', $_SESSION['car']['id']);
        $newRent->bindParam(':customerID', $customer['id']);
        $newRent->bindParam(':rentStartDate', $_SESSION['rental_data']['pickup_datetime']);
        $newRent->bindParam(':rentEndDate', $_SESSION['rental_data']['return_datetime']);
        $newRent->bindParam(':babySeats', $_SESSION['rental_data']['babySeats']);
        $newRent->bindParam(':insurance', $_SESSION['rental_data']['insurance']);
        $newRent->bindParam(':returnLocation', $_SESSION['rental_data']['returnLocation']);
        $newRent->execute();
        $rentId = $pdo->lastInsertId();

        $newInvice = 'INSERT INTO invoices (  paymentID ,amount,rentalID ) values ( :paymentID, :amount ,:rentalID)';
        $newInvice = $pdo->prepare($newInvice);
        $newInvice->bindParam(':paymentID', $paymentId);
        $amount = calculate_total_amount();
        $newInvice->bindParam(':amount', $amount);
        $newInvice->bindParam(':rentalID', $rentId);
        $newInvice->execute();
        $invoiceId = $pdo->lastInsertId();


        $updateCarStatus = 'UPDATE cars SET carStatus = 2 WHERE id = :id';
        $updateCarStatus = $pdo->prepare($updateCarStatus);
        $updateCarStatus->bindParam(':id', $_SESSION['car']['id']);
        $updateCarStatus->execute();


        $_SESSION['MSG_RENT_SUCCESS'] = 'Rent car Successfully , Invoice ID: ' . $invoiceId;
        header('Location: viewRentedCars.php');
        exit();
    } else {
        $message = 'The entered name does not match with the name of the current user or the entered date is not current date!';
    }
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Payment</title>
</head>
<body>

<?php my_header(); ?>

<main class="containerRentStepTwo">
    <section class="payment-section">
        <p class="invoiceID">Invoice Date: <?php echo(date('d-m-Y H-i')); ?></p>
        <div class="invoice-container">
            <label class="label_in_rent_car_step_two"> Customer ID:
                <label class="valueInRentStepTwo"> <?php echo $customer['id'] ?></label>
            </label>
            <label class="label_in_rent_car_step_two"> Customer Name: <label
                        class="valueInRentStepTwo"><?php echo $customer['name'] ?></label> </label>
        </div>
        <div class="invoice-container">
            <label class="label_in_rent_car_step_two"> Customer Telephone: <label class="valueInRentStepTwo"><?php echo $customer['telephone'] ?></label> </label>
            <label class="label_in_rent_car_step_two"> Customer
                Address:<label class="valueInRentStepTwo"> <?php echo $customer['country'] . ' - ' . $customer['city'] . ' - ' . $customer['street'] . ' - ' . $customer['flatNo'] ?></label> </label>
        </div>
        <div class="invoice-container">
            <label class="label_in_rent_car_step_two"> Car model: <label class="valueInRentStepTwo"><?php echo $_SESSION['car']['model'] ?></label></label>
            <label class="label_in_rent_car_step_two"> Car type:<label class="valueInRentStepTwo"> <?php echo $_SESSION['car']['type'] ?></label></label>
        </div>
        <div class="invoice-container">
            <label class="label_in_rent_car_step_two"> Car fuel type: <label class="valueInRentStepTwo"><?php echo $_SESSION['car']['fuelType'] ?></label></label>
            <?php
            $querey = 'SELECT * FROM locations l WHERE l.id = :id';
            $stmLocation = $pdo->prepare($querey);
            $stmLocation->bindParam(':id', $_SESSION['car']['locationID']);
            $stmLocation->execute();
            $location = $stmLocation->fetch();
            ?>
            <label class="label_in_rent_car_step_two"> Car pickup
                location:<label class="valueInRentStepTwo"> <?php echo($location['city'] . ' - ' . $location['pickupName']); ?></label> </label>
        </div>

        <div class="invoice-container">
            <label class="label_in_rent_car_step_two"> Car pickup date time:
                <label class="valueInRentStepTwo"><?php echo date_format(date_create($_SESSION['rental_data']['pickup_datetime']), 'd-m-Y H-i') ?></label>
            </label>

            <?php
            if (isset($_SESSION['rental_data']['returnLocation'])) {
                $stmLocation->bindParam(':id', $_SESSION['rental_data']['returnLocation']);
                $stmLocation->execute();
                $location = $stmLocation->fetch();
            }
            ?>


            <label class="label_in_rent_car_step_two"> Car return
                location: <label class="valueInRentStepTwo"><?php echo($location['city'] . ' - ' . $location['pickupName']); ?></label></label>
        </div>
        <div class="invoice-container">
            <label class="label_in_rent_car_step_two"> Return date time:
                <label class="valueInRentStepTwo"><?php
                echo date_format(date_create($_SESSION['rental_data']['pickup_datetime']), 'd-m-Y H-i')
                ?></label>
            </label>

            <label class="label_in_rent_car_step_two"> Baby
                seats: <label class="valueInRentStepTwo"><?php echo !empty($_SESSION['rental_data']['babySeat']) ? $_SESSION['rental_data']['babySeat'] : 0 ?></label></label>
        </div>
        <div class="invoice-container">
            <label class="label_in_rent_car_step_two">
                Insurance:<label class="valueInRentStepTwo"> <?php echo isset($_SESSION['rental_data']['insurance']) && $_SESSION['rental_data']['insurance'] == 1 ? 'Yes' : 'No' ?></label></label>
            <label class="label_in_rent_car_step_two">Total Amount: $<label class="valueInRentStepTwo"><?php echo(calculate_total_amount()); ?></label></label>
        </div>

        <form method="post" action="">
            <fieldset>
                <legend>Payment Information</legend>
                <?php if ($_SESSION['rental_data']['whichCredit'] == 2) : ?>
                    <div class=filter-row>
                        <label for="cc_number">Credit Card Number:</label>
                        <input type="text" id="cc_number" name="cc_number" pattern="\d{9}" required>
                        <span class="error-message">This field is required.</span>
                    </div>
                    <div class="filter-row">
                        <label for="cc_expiration">Expiration Date:</label>
                        <input type="month" id="cc_expiration" name="cc_expiration" required>
                        <span class="error-message">This field is required.</span>
                    </div>
                    <div class="filter-row">
                        <label for="cc_holder">Card Holder Name:</label>
                        <input type="text" id="cc_holder" name="cc_holder" required>
                        <span class="error-message">This field is required.</span>
                    </div>
                    <div class="filter-row">
                        <label for="cc_bank">Bank Issued:</label>
                        <input type="text" id="cc_bank" name="cc_bank" required>
                        <span class="error-message">This field is required.</span>
                    </div>
                    <div class="filter-row">
                        <label>Credit Card Type:</label>

                        <div class="card-options">
                            <div class="card-option">
                                <label class="card-type">
                                    <img src="./carsImages/masterCard.png" alt="MasterCard">
                                    <input type="radio" name="cc_type" value="MasterCard" required> MasterCard
                                    <span class="error-message">This field is required.</span>
                                </label>
                            </div>
                            <div class="card-option">
                                <label class="card-type">
                                    <img src="./carsImages/Old_Visa_Logo.svg-removebg-preview.png" alt="Visa">
                                    <input type="radio" name="cc_type" value="Visa" required> Visa
                                    <span class="error-message">This field is required.</span>
                                </label>
                            </div>
                        </div>

                    </div>
                <?php endif; ?>

                <div class="filter-row">
                    <label for="confirm_name">Name:</label>
                    <input type="text" id="confirm_name" name="confirm_name" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label for="confirm_date">Date:</label>
                    <input type="date" id="confirm_date" name="confirm_date" required>
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label>
                        <input type="checkbox" name="terms" required> I accept the terms and conditions

                    </label>
                </div>
                <div class="text_success">
                    <?php
                    if (isset($message)) {
                        echo $message;
                    }
                    ?>
                </div>
                <div>
                    <button type="submit" name="ConfirmRent" value="Confirm Rent" class="submit-button">
                        <img src="./carsImages/cart.png" alt="Submit Icon"> Confirm Rent
                    </button>
                </div>
            </fieldset>
        </form>
    </section>
</main>
<?php my_footer(); ?>
</body>
</html>