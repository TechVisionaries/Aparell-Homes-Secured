<?php    
    session_start();
    require_once "config.php";
?>
<?php

    $logStat = false;
    $acc = '';
    $dp = 'images/user.png';
    $message = '';
    $messageType = '';

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

    // Form processing - FIXED VERSION
    if(isset($_POST["submit"])){
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $message = 'Invalid request. Please try again.';
            $messageType = 'error';
        } else {
            // Sanitize and validate inputs
            $fname = trim(htmlspecialchars($_POST['fname'], ENT_QUOTES, 'UTF-8'));
            $lname = trim(htmlspecialchars($_POST['lname'], ENT_QUOTES, 'UTF-8'));
            $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
            $messageText = trim(htmlspecialchars($_POST['Message'], ENT_QUOTES, 'UTF-8'));
            
            // Server-side validation
            $errors = [];
            
            if (empty($fname) || strlen($fname) > 50) {
                $errors[] = "First name is required and must be less than 50 characters.";
            }
            
            if (empty($lname) || strlen($lname) > 50) {
                $errors[] = "Last name is required and must be less than 50 characters.";
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "A valid email address is required.";
            }
            
            if (empty($messageText) || strlen($messageText) > 1000) {
                $errors[] = "Message is required and must be less than 1000 characters.";
            }
            
            if (empty($errors)) {
                // Use prepared statement to prevent SQL injection
                $sqlInsert = "INSERT INTO contactus(firstName, lastName, message, email) VALUES(?, ?, ?, ?)";
                $stmt = $conn->prepare($sqlInsert);
                
                if ($stmt) {
                    $stmt->bind_param("ssss", $fname, $lname, $messageText, $email);
                    
                    if($stmt->execute()){
                        $message = 'Message Successfully Sent!';
                        $messageType = 'success';
                        // Clear form data on success
                        $_POST = array();
                    } else {
                        $message = 'Message Not Delivered! Please try again.';
                        $messageType = 'error';
                    }
                    
                    $stmt->close();
                } else {
                    $message = 'Database error occurred. Please try again later.';
                    $messageType = 'error';
                }
            } else {
                $message = implode('<br>', $errors);
                $messageType = 'error';
            }
        }
        
        mysqli_close($conn);
        
        // Regenerate CSRF token after form submission
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/contactstyle.css" id="stylesheet">
        <style>
            .message {
                padding: 15px;
                margin: 10px 0;
                border-radius: 5px;
                font-weight: bold;
            }
            .success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
        </style>
    </head>

    <body>
        <!-- Navigation panel -->
        <nav>
            <!-- Logo -->
            <a href="index.php"><img src="images/Logo(light).png" id="logo" alt="logo"></a>

            <!-- Nav buttons -->
            <div style="margin-right:20px">
                <ul>
                    <li><a href="index.php"><span class="hov">Home</span></a></li>
                    <li><a href="searchApartment.php"><span class="hov">Apartments</span></a></li>
                    <li><a href="aboutus.html"><span class="hov">About Us</span></a></li>
                    <li><a href="contactUs.php" class="active"><span class="hov">Contact Us</span></a></li>
                </ul>
            </div>
            
            <!-- Login & Signup -->
            <div id="log">
                <a href="loginHTML.php"><button id="login">Login</button></a>
                <a href="register.html"><button id="signup">Sign up</button></a>
            </div>
            
            <!-- Profile icon -->
            <div id="profile">
                <?php if ($logStat === true): ?>
                <img src="<?php echo htmlspecialchars($dp, ENT_QUOTES, 'UTF-8'); ?>" height="50" alt="profile" onmouseover="showDpNav();" onmouseout="hideDpNav();" style="border-radius:50%">
                <div>
                    <ul id="dpNav" onmouseover="showDpNav();" onmouseout="hideDpNav();">
                        <a href="<?php echo htmlspecialchars($acc, ENT_QUOTES, 'UTF-8'); ?>Dash.php"><li style="margin-top: 35px; border-top-left-radius: 5px; border-top-right-radius: 5px;">Dashboard</li></a>
                        <a href="logout.php"><li>Log Out</li></a>
                    </ul>
                </div>
                <?php else: ?>
                    <style>#profile { display: none; }</style>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Background image and text -->
        <div class="pic">
            <div class="pos70">
                <h2 class="aboutus">Contact Us</h2>
                <div style="width:70%">
                    <p class="aboutustext"><span class="textPadding"><br><br>If you have any questions or queries a member of staff will always be happy to help. Feel free to contact us by telephone or email and we will be sure to get back to you as soon as possible.</span></p>
                    <p class="abc">228/10,<br> Rockland place,<br> Colombo 07<br>
                    +94 11 2 485823<br>aparell@gmail.com</p>
                </div>
            </div>
        </div>

        <div id="contactDivs">
            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d33007.13503196978!2d79.95921982827926!3d6.920117390160202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25715a39b7545%3A0xf53f96ad77a67685!2sAparell%20Homes!5e0!3m2!1sen!2slk!4v1667638275026!5m2!1sen!2slk" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Aparell Homes Location"></iframe>
            </div>
            
            <div class="container5">
                <?php if (!empty($message)): ?>
                    <div class="message <?php echo $messageType; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <input type="text" id="fname" name="fname" placeholder="First Name" 
                           maxlength="50" value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                    <br><br>
                    
                    <input type="text" id="lname" name="lname" placeholder="Last Name" 
                           maxlength="50" value="<?php echo isset($_POST['lname']) ? htmlspecialchars($_POST['lname'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                    <br><br>
                    
                    <input type="email" id="email" name="email" placeholder="Email" 
                           pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                    <br><br>
                    
                    <textarea name="Message" placeholder="Message" style="height:200px" maxlength="1000" required><?php echo isset($_POST['Message']) ? htmlspecialchars($_POST['Message'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    <br><br><br>
                    
                    <button class="sellButton" name="submit" type="submit">Send Message</button>
                </form>
            </div>
        </div>    

        <!-- Footer -->
        <footer>
            <center>
                <!-- Social Media -->
                <div id="icons">
                    <a href="https://www.facebook.com/profile.php?id=100086685492601" target="_blank" rel="noopener"><img src="images/facebook.png" width="30" alt="Facebook"></a>
                    <a href="https://www.instagram.com/aparellhomes/" target="_blank" rel="noopener"><img src="images/instagram.png" width="30" alt="Instagram"></a>
                    <a href="https://twitter.com/AparellHomes" target="_blank" rel="noopener"><img src="images/twitter.png" width="30" alt="Twitter"></a>
                    <a href="mailto:aparellhomes@gmail.com"><img src="images/mail.png" width="30" alt="Email"></a>
                    <a href="https://wa.me/0740276949" target="_blank" rel="noopener"><img src="images/whatsapp.png" width="30" alt="WhatsApp"></a>
                </div>
                <!-- Links -->
                <div id="links">
                    <a href="aboutus.html">Info</a> &#x2022; <a href="contactUs.php">Support</a> &#x2022; <a href="contactUs.php">Marketing</a><br>
                    <a href="terms.html">Terms of Use</a> &#x2022; <a href="privacy.html">Privacy Policy</a>
                </div>
                <!-- Copyrights -->
                <div id="cpyryts">
                    &#x00A9; 2022 Aparell Homes
                </div>
            </center>
        </footer>

        <script src="js/script.js"></script>
        
        <script>
            // Show/hide login and profile sections based on login status
            <?php if ($logStat === true): ?>
                document.getElementById('log').style.display = 'none';
                document.getElementById('profile').style.display = 'block';
            <?php endif; ?>
        </script>
        
    </body>
</html>