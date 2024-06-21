<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
include('db.php.inc');
my_header();
?>
<main class="main_confirmation">
    <div class="div_confirmation_register">
        <h2>Registration Successful</h2>
        <p><a href="login.php">Click here to login</a></p>
    </div>

</main>
<?php
my_footer();
?>
</body>


</html>