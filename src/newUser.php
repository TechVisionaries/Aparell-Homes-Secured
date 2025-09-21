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

    if(isset($_SESSION['LoginStat'])){
        $logStat = $_SESSION['LoginStat'];
        
        if($_SESSION['LoginStat'] == true){

            if($_SESSION['SellerSignedIn'] == true){
                header('Location:staffDash.php');
            }
            elseif($_SESSION['BuyerSignedIn'] == true){
                header('Location:buyerDash.php');
            }
            elseif($_SESSION['StaffSignedIn'] != true){
                echo "<script>
                        alert('Please login to proceed!');
                        window.location.replace('loginHTML.php');
                    </script>";
            }
            else{
                $email = $_SESSION['Email'];
                $acc = $_SESSION['AccType'];
                $fname = $_SESSION['fName'];
                $lname = $_SESSION['lName'];
                $addrs = $_SESSION['addrs'];
                $phone = $_SESSION['mobile'];
                $pwd = $_SESSION['Pwd'];
                $dp = $_SESSION['profile'];
            }  
        }    
        else{
            echo "<script>
                            alert('Please login to proceed!');
                            window.location.replace('loginHTML.php');
                        </script>";
        }        
    }
    else{
        echo "<script>
                        alert('Please login to proceed!');
                        window.location.replace('loginHTML.php');
                    </script>";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/regStyle.css" id="stylesheet">
        <style>
            /*Nav buttons*/
            ul{
                list-style-type: none;
                margin:0px;
                padding: 0px;
                overflow:hidden;
                display: inline-block;
                position: absolute;
                left: 33%;
            }  

            li{
                float:left;
            }

            li a{
                font-family:Verdana, Geneva, Tahoma, sans-serif;
                text-decoration: none;
                display:block;
                font-size:medium;
                color:black;
                padding: 30px 20px;
            }
            /*Profile icon*/
            #profile{
                margin: 15px;
                float: right;
                display: block;
            }

            #dpNav{
                top: 35px;
                right: 10px;
                left: unset;
                border-radius: 5px;
                display: none;
            }

            #dpNav li{
                float:none;
                background: white;
                padding: 10px;
            }

            #dpNav li:hover{
                background-color: #84c6e6;
                cursor: pointer;
            }

            #dpNav li a{
                font-family:Verdana, Geneva, Tahoma, sans-serif;
                text-decoration: none;
                display:block;
                font-size:medium;
                color:black;
                padding: 30px 20px;
            }

            #dpNav a{
                text-decoration: none;
                color:black;
            }
        </style>
    </head>

    <body>

        <!-- Logo -->
        <a href="index.php"><img src="images/Logo(light).png" id="logo" alt="logo"></a>

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

        <!-- Registeration Form -->
        <div id="form">
            <form action="AdNewAcc.php" method="POST" id="regForm">
                <h1>Add New User</h1>
                
                <!-- Name -->
                <label for="fName">Name</label><br>
                <input type="text" name="fName" id="fName" placeholder="First Name" class="name" required>
                <input type="text" name="lName" id="lName" placeholder="Last Name" class="name" required>
                
                <br>

                <!-- Address -->
                <label for="addrs">Address</label><br>
                <textarea name="addrs" id="addrs" cols="46" rows="2" placeholder="Optional"></textarea>
                
                <br>

                <!-- Account type -->
                <label>I am a</label><br>
                <input type="radio" name="accType" id="buyer" value="buyer" checked><label for="buyer" style="padding-right: 10px">Buyer</label>
                <input type="radio" name="accType" id="seller" value="seller" ><label for="seller" style="padding-right: 10px">Seller</label>
                <input type="radio" name="accType" id="staff" value="staff" ><label for="staff">Staff</label>
                
                <br>

                <!-- Email -->
                <label for="email">Email</label><br>
                <input type="email" name="email" id="email" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="example@example.xyz" required>
                
                <br>

                <!-- Phone No -->
                <label for="phone">Phone No</label><br>
                <input type="tel" name="phone" id="phone" pattern="[0-9]{10}" placeholder="0715886675" required>
                
                <br>

                <div id="passwrd">
                    <!-- Password -->
                    <label for="pwd">Password</label><br>
                    <input type="password" name="pwd" id="pwd" minlength="8" placeholder="Password" required onkeyup="showImg('pwd','pwdIco');">
                    <div class="show" onclick="showPwd('pwd','pwdIco')"><img src="images/show.png" width="20px" height="20px" id="pwdIco"></div>
                </div>
                <div id="confirmPasswrd">    
                    <!-- Confirm Password -->
                    <label for="cPwd">Confirm Password</label><br>
                    <input type="password" name="cPwd" id="cPwd" placeholder="Confirm" required onkeyup="showImg('cPwd','cPwdIco');">
                    <div class="show" onclick="showPwd('cPwd','cPwdIco')"><img src="images/show.png" width="20px" height="20px" id="cPwdIco"></div>
                </div>    

                <br>

                <!-- Terms and Conditions -->
                <input type="checkbox" name="terms" id="terms" required onclick="enableButton();"><label for="terms">Accept the <a href="terms.html">Terms & Conditions</a></label>
                
                <br><br>

                <center>
                    <!-- Submit Button -->
                    <button id="submitBtn" disabled style="margin-bottom: 25px;">Create Account</button>
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

        <script src="js/regScript.js"></script>
        <script src="js/script.js"></script>
    </body>
</html>