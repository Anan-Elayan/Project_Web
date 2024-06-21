<?php
session_start();
include_once('db.php.inc');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Connect to the database
    $conn = connectionDataBase();

    // Prepare the SQL statement to find the user with matching username and password
    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE userName = :userName AND password = :password"
    );

    // Bind the parameters
    $stmt->bindParam(':userName', $_POST['username']);
    $stmt->bindParam(':password', $_POST['password']);
    $stmt->execute();

    // Fetch the result
    $user = $stmt->fetch();

    if ($user) {
        // Set session variable to indicate the user is logged in
        $_SESSION['user'] = $user; // Store user
        // Redirect based on user type
        if ($user['isManager']) {
            header("Location: managerPanel.php");
        } else {
            // Check if there is a redirect URL
            $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
            unset($_SESSION['redirect_url']); // Clear the redirect URL from session
            header("Location: $redirect_url");
        }
        exit();
    } else {
        // Handle case where no matching user is found
        $error_message = "Invalid username or password.";
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login page</title>
</head>
<body>
<?php
include_once('db.php.inc');
my_header();
?>

<main class="main_login">
    <div class="container_login">
        <form action="login.php" method="POST">
            <div class="welcome_back">
                <label for="username">Welcome Back!</label>
            </div>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <?php if ($error_message): ?>
                    <h2><?php echo htmlspecialchars($error_message); ?></h2>
                <?php endif; ?>
            <?php endif; ?>

            <div class="divUserNameLogin">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
<!--                <span class="error-message">This field is required.</span>-->
            </div>

            <div class="divUserNameLogin">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
<!--                <span class="error-message">This field is required.</span>-->
            </div>

            <button type="submit" value="Login" class="submit-button">
                <img src="./carsImages/login.png" alt="Submit Icon"> Login
            </button>
        </form>
    </div>
</main>

<?php
my_footer();
?>
</body>
</html>
