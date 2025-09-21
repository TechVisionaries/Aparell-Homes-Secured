<?php
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
    
    session_start();
    
    $_SESSION['BuyerSignedIn'] = false;
    $_SESSION['SellerSignedIn'] = false;
    $_SESSION['StaffSignedIn'] = false;
    $_SESSION['LoginStat'] = false;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/logStyle.css" id="stylesheet">
    </head>

    <body>

        <!-- Logo -->
        <a href="index.php"><img src="images/Logo(light).png" id="logo" alt="logo"></a>

        <!-- Registeration Form -->
        <div id="form">
            <form action="login.php" method="POST" id="regForm">
                <h1>Login</h1>

                <!-- Email -->
                <label for="email">Email</label><br>
                <input type="email" name="email" id="email" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                
                <br>

                <div id="passwrd">
                    <!-- Password -->
                    <label for="pwd">Password</label><br>
                    <input type="password" name="pwd" id="pwd" required onkeyup="showImg('pwd','pwdIco');">
                    <div class="show" onclick="showPwd('pwd','pwdIco')"><img src="images/show.png" width="20px" height="20px" id="pwdIco"></div>
                </div>

                <br>

                <!-- Account type -->
                <label>I am a</label><br>
                <input type="radio" name="accType" id="buyer" value="buyer" checked><label for="buyer" style="padding-right: 10px">Buyer</label>
                <input type="radio" name="accType" id="seller" value="seller"><label for="seller" style="padding-right: 10px">Seller</label>
                <input type="radio" name="accType" id="staff" value="staff" ><label for="staff">Staff</label>
                
                <br><br>

                <center>
                    <!-- Submit Button -->
                    <button id="submitBtn">Login</button>

                    <!-- login -->
                    <p>Not Registered? <a href="register.html">sign up</a></p>
                </center>
            </form>
        </div>

        <br>

        <!-- Footer -->
        <footer>
            <center>
                <!-- Copyrights -->
                <div id="cpyryt">
                    &#x00A9 2022 Aparell Homes
                </div>
            </center>
        </footer>

        <script src="js/loginScript.js"></script>
    </body>
</html>