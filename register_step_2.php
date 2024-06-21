<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['confirmPassword'] = $_POST['confirmPassword'];

    // Check if passwords match
    if ($_SESSION['password'] !== $_SESSION['confirmPassword']) {
        echo "Passwords do not match.";
        exit();
    }
    header("Location: register_step_3.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create E-Account</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
include('db.php.inc');
my_header();
?>
<main class="main_register_step_2">
    <form action="" method="POST">
        <fieldset>
            <legend class="legendRegisterStep2"> User Name and password</legend>
            <div class="container_register_step_2">
                <div class="filter-row">
                    <label for="username">Username</label>
                    <input type="text" name="username" pattern=".{6,13}" required title="6 to 13 characters">
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label for="password">Password</label>
                    <input type="password" name="password" pattern=".{8,12}" required title="8 to 12 characters">
                    <span class="error-message">This field is required.</span>
                </div>
                <div class="filter-row">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="confirmPassword" pattern=".{8,12}" required
                           title="8 to 12 characters">
                    <span class="error-message">This field is required.</span>
                </div>

            </div>
            <div class="submit-container">
                <button type="submit" value="Next Step" class="submit-button">
                    <img src="./carsImages/next_step.png" alt="Submit Icon"> Next Step
                </button>
            </div>
        </fieldset>
    </form>
</main>
<?php
my_footer();
?>
</body>

</html>