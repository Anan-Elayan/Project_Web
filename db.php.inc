<?php
define('DBNAME', 'bzu_rental_agency');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBHOST', 'localhost:3307');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function connectionDataBase($dbname = DBNAME, $dbhost = DBHOST, $dbpass = DBPASS, $dbuser = DBUSER)
{

    try {
        $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        return $conn;
    } catch (PDOException $ex) {
        die("Error: " . $ex->getMessage());
    }
}


function my_header()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>
    <header class="header">
        <figure class="logo-container">
            <img src="carsImages/logo_orange_removebg.png" alt="logo" class="logo">
        </figure>
        <div class="container_label_header">
            <h1 class="heading_header">Birzeit Car Rental Agency</h1>
        </div>
        <div class="links_container_inHeader">

            <?php if (isset($_SESSION['user']) && $_SESSION['user']): ?>
                <a class="profile" href="profile.php"><?php echo $_SESSION['user']['userName'];?></a>
                <a class="logout" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="login" href="login.php">Login</a>
                <a class="register" href="register_step_1.php">Register</a>
            <?php endif; ?>
            <a class="" href="">Cart</a>
            <a href="aboutUs.php">About us</a>
        </div>
    </header>

    <?php if (isset($_SESSION['user']) && $_SESSION['user']): ?>
    <nav class="nav_header">
        <?php if ($_SESSION['user']['isManager'] === 1): ?>
            <a class="home" href="index.php">Home</a>
            <a class="home" href="managerPanel.php">Panel</a>
        <?php else: ?>
            <a class="home" href="index.php">Home</a>
            <a class="returnCar" href="returnCar.php">Return Car</a>
            <a class="viewRentedCars" href="viewRentedCars.php">View rented cars</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
    <?php
}


function my_footer()
{
    ?>
    <footer class="footer" id="footer">

    </footer>

    <div class="containerFooter">
        <nav class="nav_footer">
            <figure class="logo-container">
                <img src="carsImages/logo_orange_removebg.png" alt="logo" width="90px" class="logo">
            </figure>
            <p class="footer_title"><span>Car Rental Agency</span>&copy; 2024</p>
            <p>
                <small>Phone number: +972 59911542</small><br>
                <small>Email: bazuRentelCar@gmail.com</small><br>
                <small>Location: Rammallah Al-ersal street</small><br>
                <small>Last update:
                    <time>10:36 PM</time>
                </small><br>
                <a href="">Contact Us</a>
            </p>

        </nav>
    </div>
    <?php
}
