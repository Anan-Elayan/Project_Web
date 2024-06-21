<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Added Successfully</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<h1>Car added successfully.</h1>
<?php

if (isset($_GET['id'])) {
    echo '<h3>Car ID: ' . ($_GET['id']) . '</h3>';
}
?>
</body>
</html>
