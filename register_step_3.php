<?php
session_start();
include('db.php.inc');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Connect to the database
    $conn = connectionDataBase();

    // Prepare the SQL statement
    $stmt = $conn->prepare(
        "INSERT INTO users (userName, password, isManager) VALUES (:userName, :password, 0)"
    );
    // Bind the parameters
    $stmt->bindParam(':userName', $_SESSION['username']);
    $stmt->bindParam(':password', $_SESSION['password']);
    $stmt->execute();


    $stmtPayment = $conn->prepare(
        "INSERT INTO payments (creditCardNumber, creditCardExpiry, creditCardName,bankIssued)
                                        VALUES (:creditCardNumber, :creditCardExpiry, :creditCardName, :bankIssued)"
    );
    // Bind the parameters
    $stmtPayment->bindParam(':creditCardNumber', $_SESSION['creditCardNumber']);
    $stmtPayment->bindParam(':creditCardExpiry', $_SESSION['expirationDate']);
    $stmtPayment->bindParam(':creditCardName', $_SESSION['holderName']);
    $stmtPayment->bindParam(':bankIssued', $_SESSION['$bankIssued']);
    $stmtPayment->execute();


    $stmtGetAllUsers = $conn->prepare(
        "SELECT u.id FROM users u ORDER BY id DESC LIMIT 1"
    );
    $stmtGetAllUsers->execute();
    $lastUser = $stmtGetAllUsers->fetch();
    $id = $lastUser['id'];

    $stmtGetAllPayment = $conn->prepare(
        "SELECT p.id FROM payments p ORDER BY id DESC LIMIT 1"
    );
    $stmtGetAllPayment->execute();
    $lastPayment = $stmtGetAllPayment->fetch();
    $idPayment = $lastPayment['id'];


    $stmtCustomer = $conn->prepare(
        "INSERT INTO customers (name, houseNo, flatNo,street,city,country,dateOfBirth,idNumber,email,telephone,userID ,paymentID )
                                        VALUES (:name, :houseNo, :flatNo, :street, :city, :country, :dateOfBirth,:idNumber,:email,:telephone,:userID, :paymentID)"
    );
    // Bind the parameters
    $stmtCustomer->bindParam(':name', $_SESSION['name']);
    $stmtCustomer->bindParam(':houseNo', $_SESSION['houseNo']);
    $stmtCustomer->bindParam(':flatNo', $_SESSION['flatNo']);
    $stmtCustomer->bindParam(':street', $_SESSION['street']);
    $stmtCustomer->bindParam(':city', $_SESSION['city']);
    $stmtCustomer->bindParam(':country', $_SESSION['country']);
    $stmtCustomer->bindParam(':dateOfBirth', $_SESSION['dateOfBirth']);
    $stmtCustomer->bindParam(':idNumber', $_SESSION['idNumber']);
    $stmtCustomer->bindParam(':email', $_SESSION['email']);
    $stmtCustomer->bindParam(':telephone', $_SESSION['telephone']);
    $stmtCustomer->bindParam(':userID', $id);
    $stmtCustomer->bindParam(':paymentID', $idPayment);
    $stmtCustomer->execute();
    header('Location:confirmation_page.php');


}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Details</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
my_header();
?>
<main class="main_register_step_3">
    <form action="" method="POST">
        <fieldset>
            <legend class="legendRegisterStep2"> Confirmation Register</legend>
            <div class="container_register_step_3">

                <div>
                    <label class="lblRegisterStep3"> Name:</label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['name']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Email:</label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['email']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Date Of Birth: </label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['dateOfBirth']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">ID Number: </label>
                    <label class="lblValueRegisterStep3"> <?php echo $_SESSION['idNumber']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Telephone:</label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['telephone']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3"> Email: </label>
                    <label class="lblValueRegisterStep3"> <?php echo $_SESSION['email']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Flat No: </label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['flatNo']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">House No:</label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['houseNo']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Street: </label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['street']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">City:</label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['city']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Country: </label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['country']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">CreditCard Number: </label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['creditCardNumber']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Expiration Date:</label>
                    <label class="lblValueRegisterStep3"> <?php echo $_SESSION['expirationDate']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Holder Name:</label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['holderName']; ?></label>
                </div>

                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Bank: </label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['$bankIssued']; ?></label>
                </div>
                <div class="divRegisterStep3">
                    <label class="lblRegisterStep3">Username:</label>
                    <label class="lblValueRegisterStep3"><?php echo $_SESSION['username']; ?></label>
                </div>

                <button type="submit" value="Login" class="submit-button " style="margin-top: 20px">
                    <img src="./carsImages/login.png" alt="Submit Icon"> Confirm and Register
                </button>


        </fieldset>
    </form>
</main>
<?php
my_footer();
?>
</body>

</html>