<?php
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
                    <!-- Add this inside your form, before the submit button -->
                    <div style="text-align: center; margin: 20px 0;">
                        <p>Or login with:</p>
                        <a href="google-login.php" style="border-radius: 5px; text-decoration: none; display: inline-block;">
                            <img src="images/google-icon.png" width="200" style="vertical-align: middle; margin-right: 5px;">
                        </a>
                    </div>
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