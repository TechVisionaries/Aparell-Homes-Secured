
<?php
require_once "config.php";
include 'includes/security-headers.php';
// Disable display of errors to users
    ini_set('display_errors', 'Off');
    ini_set('display_startup_errors', 'Off');
    error_reporting(E_ALL);

    // Enable logging to file
    ini_set('log_errors', 'On');
    ini_set('error_log', '../logs/php-errors.log');

    // Custom error handler
    set_error_handler(function ($severity, $message, $file, $line) {
        if (error_reporting() & $severity) {
            error_log("[$severity] $message in $file on line $line");
            header('Location: error.php');
            exit();
        }
    });  

    // Handle fatal errors
    register_shutdown_function(function () {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR, E_USER_ERROR])) {
            error_log("Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}");
            include 'error.php';
            exit();
        }
    });
?>
<?php
    require "checkAccTypeSeller.php";
    /*session_start();

    if(isset($_SESSION['LoginStat'])){
        $logStat = $_SESSION['LoginStat'];

        if($_SESSION['LoginStat'] == true){

            $acc = $_SESSION['AccType'];
            $dp = $_SESSION['profile'];
            
        }   

    }*/
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Payment | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/payment.css" id="stylesheet">
    </head>

    <body>

        <!-- Navigation panel -->
        <nav>

            <!-- Logo -->
            <a href="index.php"><img src="images/Logo(light).png" id="logo" alt="logo"></a>

            <!-- Nav buttons -->
            <ul>
                <li><a href="index.php" ><font class="hov">Home</font></a></li>
                <li><a href="searchApartment.php" class="active"><font class="hov">Apartments</font></a></li>
                <li><a href="aboutus.html"><font class="hov">About Us</font></a></li>
                <li><a href="contactUs.php" ><font class="hov">Contact Us</font></a></li>
            </ul>

            <!-- Login & Signup -->
            <div id="log">
                <a href="loginHTML.php"><button id="login">Login</button></a>
                <a href="register.html"><button id="signup">Sign up</button></a>
            </div>

            <!-- Profile icon -->
            <div id="profile">
                <img src="<?php echo $dp ?>" height="50px" alt="profile" onmouseover="showDpNav();" onmouseout="hideDpNav();" style="border-radius:50%";>
                <div>
                    <ul id="dpNav" onmouseover="showDpNav();" onmouseout="hideDpNav();">
                        <a href="<?php echo $acc ?>Dash.php"><li style="margin-top: 35px; border-top-left-radius: 5px; border-top-right-radius: 5px;">Dashboard</li></a>
                        <a href="logout.php"><li>Log Out</li></a>
                    </ul>
                </div>
            </div>

            <!-- Dark Mode toggle switch
            <div id="drkmode">
                <img src="images/night.png" alt="dark" id="dark" class="mode" width="20px" onclick="light('OFF')">
                <img src="images/day.png" alt="light" id="light" class="mode" width="20px" onclick="light('ON')">
            </div> -->

        </nav>
        <div class="pic">
      
        <div class="paymentArea">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            
                <label><h1 id="chooseSub">Choose Subcription</h1></label>
                <div class="pack">
                <input type="radio" name="subcription" id="sub1" value="3000">
                <label for="sub1"><font class="headings">Rs 3000 </font> <p class="subDes">To boost for the top<p>  </label>
                </div>
                <div class="pack">
                <input type="radio" name="subcription" id="sub2" value="1500">
                <label for="sub2"><font class="headings">Rs 1500 </font><br> <p class="subDes">To boost upto 10 ads<p> </label>
                </div><br>
                <label><font class="headings">Email</font></label><br>
                <input type="email" name="email" id="email" placeholder="Email"><br>
                <label><font class="headings">Payment Type</font></label><br>
                <select name="payType">
                    <option value="Mater card">Master card</option>
                    <option value="credit card">Credit card</option>
                    <option value="Paypal">Paypal</option>
                </select><br>
                <label><font class="headings">Card Number</font></label><br>
                <input type="number" name="cardNo" id="cardNo" placeholder="Card Number XXXXXXXXXXXX" maxlenth="16" minlenth="16"><br>
                <label><font class="headings">Name on Card</font></label><br>
                <input type="text" name="cardName" id="cardName" placeholder="Name on Card"><br>
                <label><font class="headings">Expiery Date</font></label><br>
                
                <input type="text" name="ExDate" id="ExDate" placeholder="MM/YY" pattern="[0-1][0-2]/[0-9][0-9]"><br>

                <input type="submit" name="submitbtnPay" value="Pay" id="paybtn">
            </form>
        </div>
</div>
        <?php
        require_once "config.php";
        if(isset($_POST['submitbtnPay'])){
            $package = $_POST['subcription'];
            $email = $_POST['email'];
            $typePay = $_POST['payType'];
            $cardno = $_POST['cardNo'];
            $name = $_POST['cardName'];
            $expDate = $_POST['ExDate'];

            $sqlContact = " insert into payment(paymentType,package,email,cardNo,name,expiryDate) values('$typePay', '$package', '$email','$cardno','$name','$expDate')";
            if(mysqli_query($conn,$sqlContact)){
                echo "<script>
                        alert('Payment Successfully!');
                        window.location.replace('payment.php');
                      </script>";
                
            }
            else{
                echo "<script>
                        alert('Payment Unsuccessfully!');
                        window.location.replace('payment.php');
                      </script>";
            }
        
        
            mysqli_close($conn);
        }

        ?>


        
        
         

        <!-- Footer -->
        <footer>
            <center>
                <!-- Social Media -->
                <div id="icons">
                    <a href="https://www.facebook.com/profile.php?id=100086685492601" target="_blank"><img src="images/facebook.png" width="30px"></a>
                    <a href="https://www.instagram.com/aparellhomes/" target="_blank"><img src="images/instagram.png" width="30px"></a>
                    <a href="https://twitter.com/AparellHomes" target="_blank"><img src="images/twitter.png" width="30px"></a>
                    <a href="mailto: aparellhomes@gmail.com"><img src="images/mail.png" width="30px"></a>
                    <a href="https://wa.me/0740276949" target="_blank"><img src="images/whatsapp.png" width="30px"></a>
                </div>
                <!-- Links -->
                <div id="links">
                    <a href="aboutus.html">Info</a> &#x2022 <a href="contactUs.php">Support</a> &#x2022 <a href="contactUs.php">Marketing</a><br>
                    <a href="terms.html">Terms of Use</a> &#x2022 <a href="privacy.html">Privacy Policy</a>
                </div>
                <!-- Copyrights -->
                <div id="cpyryt">
                    &#x00A9 2022 Aparell Homes
                </div>
            </center>
        </footer>

        <script src="js/script.js"></script>
    </body>
</html>

<?php
    if(isset($_SESSION['LoginStat'])){
        if($logStat == true){
        //if logged in
            echo "<script>
                    document.getElementById('log').style.display = 'none';
                    document.getElementById('profile').style.display = 'block';
                </script>";
        }
    }
?>