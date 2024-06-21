<?php
session_start();
include_once('db.php.inc');

if (!isset($_GET['invoice_id'])) {
    header('Location: rent.php');
    exit();
}

$invoice_id = $_GET['invoice_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Confirmation</title>
</head>
<body>

<?php my_header(); ?>
<main>
    <article class="confirmationRent">
        <h2>Thank You!</h2>
        <p>Your car has been successfully rented.</p>
        <p>Invoice ID: <?php echo htmlspecialchars($invoice_id); ?></p>
    </article>
</main>
<hr>
<?php my_footer(); ?>
</body>
</html>
