
<?php
require_once "config.php";
?>
<?php
    session_start();

    // Generate CSRF token if it doesn't exist
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if(isset($_SESSION['LoginStat'])){
        $logStat = $_SESSION['LoginStat'];

        if($_SESSION['LoginStat'] == true){

            $acc = $_SESSION['AccType'];
            $dp = $_SESSION['profile'];

        }   

    }


?>
<!DOCTYPE html>
<html>
    <head>
        <title>About Us | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/contactstyle.css" id="stylesheet">
    </head>

    <body>

        <!-- Navigation panel -->
        <nav>

            <!-- Logo -->
            <a href="index.php"><img src="images/Logo(light).png" id="logo" alt="logo"></a>

            <!-- Nav buttons -->
            <div style="margin-right:20px">
                <ul>
                    <li><a href="index.php"><font class="hov">Home</font></a></li>
                    <li><a href="searchApartment.php"><font class="hov">Apartments</font></a></li>
                    <li><a href="aboutus.html"  ><font class="hov">About Us</font></a></li>
                    <li><a href="contactUs.php" class="active"><font class="hov">Contact Us</font></a></li>
                </ul>
            </div>
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
        <!--background image and text-->
        <div class="pic">
            <div class="pos70">
                <h2 class="aboutus">Contact Us</h2>
                <div style="width:70%">
                    <p class="aboutustext"><font class="textPadding"><br><br>If you have any questions or queries a member of staff will always be happy to help.Feel free to contact us by telephone or email and we will be sure to get back to you as soon possible.</font></p>
                    <p class="abc">228/10,<br> Rockland place,<br> Colombo 07<br>
                    +94 11 2 485823<br>aparell@gmail.com</p>
                </div>
            </div>
        </div>
        <div id="contactDivs">
            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d33007.13503196978!2d79.95921982827926!3d6.920117390160202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25715a39b7545%3A0xf53f96ad77a67685!2sAparell%20Homes!5e0!3m2!1sen!2slk!4v1667638275026!5m2!1sen!2slk" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="container5">
                <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="text" id="fname" name="fname" placeholder="First Name" required>
                <br><br>
                <input type="text" id="lname" name="lname" placeholder="Last Name" required>
                <br><br>
                <input type="email" id="email" name="email" placeholder="Email" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                <br><br>
                <textarea  name="Message" placeholder="Message" style="height:200px" required></textarea>
                <br><br><br>
                <button class="sellButton" name="submit">Send Message</button>
                </form>
            </div>
        </div>    
        

        
        
        <?php
        if(isset($_POST["submit"])){
            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                echo "<script>
                        alert('Invalid request. Please try again.');
                        window.location.href=('contactUs.php');
                      </script>";
                exit();
            }

            $fname=htmlspecialchars($_POST['fname']);
            $lname=htmlspecialchars($_POST['lname']);
            $Message=htmlspecialchars($_POST['Message']);
            $email=htmlspecialchars($_POST['email']);

            $sqlInsert = "INSERT INTO contactus(firstName,lastName,message,email) VALUES('$fname','$lname','$Message','$email');";
            if(mysqli_query($conn,$sqlInsert)){
                echo "<script>
                        alert('Massege Successfully Sent!');
                        window.location.href=('contactUs.php');
                      </script>";
                
            }
            else{
                echo "<script>
                        alert('Massege Not Delivered!');
                        window.location.href=('contactUs.php');
                      </script>";
            }
        }
    
        mysqli_close($conn);
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
                <div id="cpyryts">
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
