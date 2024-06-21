<?php
session_start();
include_once('db.php.inc');

// Check if the user is logged in
if (!isset($_SESSION['user']) || !$_SESSION['user'] ) {
    header("Location: login.php");
    exit();
}

// Fetch user information from the database
$user_id = $_SESSION['user']['id'];

$conn = connectionDataBase();

$stmt = $conn->prepare("
    SELECT u.userName, u.password, c.*
    FROM users u
    JOIN customers c ON u.id = c.userID
    WHERE u.id = :id
");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    header("Location:managerProfile.php");
    exit();
//    echo "";
//    echo "<br>";
//    echo "<a href='managerPanel.php'>return back</a>";

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $conn = connectionDataBase();

    try {


        // Update the `users` table
        $stmt1 = $conn->prepare("
            UPDATE users
            SET userName = :username, password = :password
            WHERE id = :id
        ");
        $stmt1->bindParam(':username', $_POST['username']);
        $stmt1->bindParam(':password', $_POST['password']);
        $stmt1->bindParam(':id', $user_id);
        $stmt1->execute();

        // Update the `customers` table
        $stmt2 = $conn->prepare("
            UPDATE customers
            SET 
                name = :name,
                email = :email,
                telephone = :telephone,
                street = :street,
                houseNo = :houseNo,
                flatNo = :flatNo,
                city = :city,
                country = :country,
                dateOfBirth = :dateOfBirth,
                idNumber = :idNumber
            WHERE userID = :user_id
        ");
        $stmt2->bindParam(':name', $_POST['name']);
        $stmt2->bindParam(':email', $_POST['email']);
        $stmt2->bindParam(':telephone', $_POST['telephone']);
        $stmt2->bindParam(':street', $_POST['street']);
        $stmt2->bindParam(':houseNo', $_POST['houseNo']);
        $stmt2->bindParam(':flatNo', $_POST['flatNo']);
        $stmt2->bindParam(':city', $_POST['city']);
        $stmt2->bindParam(':country', $_POST['country']);
        $stmt2->bindParam(':dateOfBirth', $_POST['dateOfBirth']);
        $stmt2->bindParam(':idNumber', $_POST['idNumber']);
        $stmt2->bindParam(':user_id', $user_id);
        $stmt2->execute();


        $message = "Profile updated successfully.";
    } catch (Exception $e) {
        echo "Error updating profile: " . $e->getMessage();
    }
}
?>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

<?php
my_header();
?>

<main class="main_profile">
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <h4 style="color: #ec7710; text-align: center;"><?php echo htmlspecialchars($message); ?></h4>
    <?php endif; ?>
    <form method="post" action="">
        <fieldset>
            <legend>Personal Information</legend>

            <div class="container_profile">
                <p class="filter-row">
                    <strong>id:</strong>
                    <input type="text" name='id' disabled value="<?php echo htmlspecialchars($user['id']); ?>">
                </p>

                <p class="filter-row">
                    <strong>Name:</strong>
                    <input type="text" name='name' value="<?php echo htmlspecialchars($user['name']); ?>"
                </p>
                <p class="filter-row">
                    <strong>User name:</strong>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['userName']); ?>"
                           required>
                </p>

                <p class="filter-row">
                    <strong>Email:</strong>
                    <input type="text" name='email' value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </p>
                <p class="filter-row">
                    <strong>Phone:</strong>
                    <input type="number" name='telephone' value="<?php echo htmlspecialchars($user['telephone']); ?>"
                           required>
                </p>
                <p class="filter-row">
                    <strong>Street:</strong>
                    <input type="text" name='street' value="<?php echo htmlspecialchars($user['street']); ?>" required>
                </p>
                <p class="filter-row">
                    <strong>HouseNo:</strong>
                    <input type="number" name='houseNo' value="<?php echo htmlspecialchars($user['houseNo']); ?>"
                           required>
                </p>
                <p class="filter-row">
                    <strong>FlatNo:</strong>
                    <input type="number" name='flatNo' value="<?php echo htmlspecialchars($user['flatNo']); ?>"
                           required>
                </p>
                <p class="filter-row">
                    <strong>City:</strong>
                    <input type="text" name='city' value="<?php echo htmlspecialchars($user['city']); ?>" required>
                </p>
                <p class="filter-row">
                    <strong>Country:</strong>
                    <input type="text" name="country" value="<?php echo htmlspecialchars($user['country']); ?>"
                           required>
                </p>
                <p class="filter-row">
                    <strong>Date Of Birth:</strong>
                    <input type="date" name="dateOfBirth" value="<?php echo htmlspecialchars($user['dateOfBirth']); ?>"
                           required>
                </p>
                <p class="filter-row">
                    <strong>ID Number:</strong>
                    <input type="text" name="idNumber" value="<?php echo htmlspecialchars($user['idNumber']); ?>"
                           required placeholder="900000000">
                </p>
                <p class="filter-row">
                    <strong>Password:</strong>
                    <input name="password" value="<?php echo($user['password']); ?>" required>
                </p>
                <p class="filter-row">
                    <button type="submit" value="Update" name="update" class="submit-button">
                        <img src="./carsImages/update.png" alt="Submit Icon"> Update
                    </button>
                </p>
        </fieldset>
        </div>
    </form>
</main>

<?php
my_footer();
?>

</body>

</html>