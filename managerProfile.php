<?php
include_once('db.php.inc');

// Check if the user is logged in
if (!isset($_SESSION['user']) || !$_SESSION['user']) {
    header("Location: login.php");
    exit();
}

// Fetch user information from the database
$user_id = $_SESSION['user']['id'];

$conn = connectionDataBase();

$stmt = $conn->prepare("
    SELECT u.userName, u.password FROM users u
");
$stmt->execute();
$user = $stmt->fetch();
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Profile Manager</title>
</head>
<body>
<?php
include_once('db.php.inc');
my_header();
?>

<main class="main_login">
    <article>
        <div>
            <label style="font-size: 30px">Welcome Manager <?php echo $user['userName'] ?> </label>
        </div>
        <div style="padding-top: 20px;font-size: 20px">I am sorry can't update profile 😒</div>
        <div>
            <a href="managerPanel.php" style="padding-top: 20px;font-size: 20px">Click her to go panel</a>
        </div>
    </article>
</main>

<?php
my_footer();
?>
</html>
