<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['name'] = $_POST['name'];
    $_SESSION['dateOfBirth'] = $_POST['dateOfBirth'];
    $_SESSION['idNumber'] = $_POST['idNumber'];
    $_SESSION['telephone'] = $_POST['telephone'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['flatNo'] = $_POST['flatNo'];
    $_SESSION['houseNo'] = $_POST['houseNo'];
    $_SESSION['street'] = $_POST['street'];
    $_SESSION['city'] = $_POST['city'];
    $_SESSION['country'] = $_POST['country'];
    $_SESSION['creditCardNumber'] = $_POST['creditCardNumber'];
    $_SESSION['expirationDate'] = $_POST['expirationDate'];
    $_SESSION['holderName'] = $_POST['holderName'];
    $_SESSION['$bankIssued'] = $_POST['$bankIssued'];
    header('Location:register_step_2.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>

<body>
<?php
include('db.php.inc');
my_header();
?>
<main class="main-registration">
    <form action="" method="POST">
        <fieldset >
            <legend class="legendAddCar"> Registration</legend>
            <div class="container_registration">
                <div class="row">
                    <div class="div_name">
                        <p>
                            <label class="lblAtTheTopRegister" for="personalInformation">Personal Information</label>
                        </p>
                        <div class="filter-row">
                            <label for="name">Name</label>
                            <input type="text" name="name" required>
                            <span class="error-message">This field is required.</span>
                        </div>
                        <div class="filter-row">
                            <label for="dateOfBirth">Date of Birth</label>
                            <input type="date" name="dateOfBirth" required>
                            <span class="error-message">This field is required.</span>
                        </div>
                        <div class="filter-row">
                            <label for="idNumber">Id Number</label>
                            <input type="text" name="idNumber" required>
                            <span class="error-message">This field is required.</span>
                        </div>
                        <div class="filter-row">
                            <label for="telephone">Telephone</label>
                            <input type="text" name="telephone" required>
                            <span class="error-message">This field is required.</span>
                        </div>
                        <div class="filter-row">
                            <label for="email">E-mail address</label>
                            <input type="text" name="email" required>
                            <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                    <div class="div_address">
                        <p>
                            <label class="lblAtTheTopRegister" for="address">Address
                            </label>
                        </p>

                        <div class="filter-row">
                            <label for="flat">Flat No</label>
                            <input type="number" name="flatNo" required>
                            <span class="error-message">This field is required.</span>
                        </div>

                        <div class="filter-row">
                            <label for="houseNo">House No</label>
                            <input type="text" name="houseNo" required>
                            <span class="error-message">This field is required.</span>
                        </div>
                        <div class="filter-row">
                            <label for="street">Street</label>
                            <input type="text" name="street" required>
                            <span class="error-message">This field is required.</span>
                        </div>
                        <div class="filter-row">
                            <label for="city">City</label>
                            <input type="text" name="city" required>
                            <span class="error-message">This field is required.</span>
                        </div>

                        <div class="filter-row">
                            <label for="country">Country</label>
                            <input type="text" name="country" required>
                            <span class="error-message">This field is required.</span>
                        </div>
                    </div>
                </div>

                <div class="div_creditCard">
                    <p >
                        <label class="lblAtTheTopRegister" for="credit_card">Credit card details</label>
                    </p>
                    <div class="filter-row">
                        <label for="creditCardNumber">Credit Card Number</label>
                        <input type="text" name="creditCardNumber" required>
                        <span class="error-message">This field is required.</span>
                    </div>

                    <div class="filter-row">
                        <label for="expirationDate">Expiration Date</label>
                        <input type="date" name="expirationDate" required>
                        <span class="error-message">This field is required.</span>
                    </div>
                    <div class="filter-row">
                        <label for="holderName">Holder Name</label>
                        <input type="text" name="holderName" required>
                        <span class="error-message">This field is required.</span>
                    </div>
                    <div class="filter-row">
                        <label for="$bankIssued">Bank Issued</label>
                        <input type="text" name="$bankIssued" required>
                        <span class="error-message">This field is required.</span>
                    </div>
                </div>

                <div class="submit-container">
                    <button type="submit" value="Next Step" class="submit-button">
                        <img src="./carsImages/next_step.png" alt="Submit Icon"> Next Step
                    </button>
                </div>
            </div>
        </fieldset>
    </form>
</main>
<?php
my_footer();
?>
</body>

</html>