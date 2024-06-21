<?php
session_start();

// Destroy the session.
session_unset();
session_destroy();

// Redirect to the index page.
header("Location: login.php");
exit();