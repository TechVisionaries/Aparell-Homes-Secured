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

            if($_SESSION['StaffSignedIn'] == true){
                header('Location:staffDash.php');
            }
            elseif($_SESSION['SellerSignedIn'] == true){
                header('Location:buyerDash.php');
            }
            elseif($_SESSION['BuyerSignedIn'] != true){
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
        <title>Settings | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/lightstyle.css" >
    </head>
    <style>
input[type=text], select {
  width: 50%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
input[type=password], select {
  width: 50%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 50%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
input[type=button] {
  width: 10%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 100%;
  cursor: pointer;
  position: absolute;
    left: 45%;
    top: 400px;
    border-radius: 4;
    font-family: "Arial", Impact, Charcoal, sans-serif;
    font-weight: bold;
    font-size:13px;

    text-shadow: 2px 2px 4px black;
    padding: 5px 10px;
    margin:5px 60px;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  background-color: #f2f2f2;
}
.form{
    margin-left:10%;
    display:inline;

}
.formName{
    margin-left: 10%;
    margin-top: 20px;
}
.profiles{
    display:flex;
    justify-content: space-around;
    margin:20px 80px;
}
.profile1{
    flex-basis: 260px;
}
.profile1 .profile1-img{
    height: 260px;
    width:260px;
    border-radius: 50%;
    transition: 400ms;
}
.profile1 .editicon{
    height: 60px;
    width:60;
    border-radius: 100%;
}

.position{
    font-size: 13px;
    font-weight: 1000;
    letter-spacing: 1px;
    color:#093d72;
}
.user-name{
    font-size: 18px;
    font-weight: 100;
    letter-spacing: 2px;
    color:#021930;
    text-align:center;
}

.profile1 p{
    font-size: 16px;
    margin-top:20px;
    text-align:justify;
}
#profile{
    display:block;
}
#addrs{
    margin-bottom: 20px;
    padding: 10px;
}

.show{
    width: 25px;
    height: 20px;
    background: white;
    position: absolute;
    display: inline-block;
    margin-left: 234px;
    margin-top: -39px;
    cursor: pointer;
}

#pwdIco{
    padding: 0px 5px;
    background: white;
    display: none;
}
.show{
    margin-left: -35px;
    margin-top: 20px;
}
#delete{
    padding: 14px 19%;
    background: #f44336;
    border: 0;
    border-radius: 4px;
    color: white;
    cursor: pointer;    
    margin: 140px 0px 70px 106px;
}
#delete:hover{
    background: #ff5722;
}
</style>

    <body>

        <!-- Navigation panel -->
        <nav>

            <!-- Logo -->
            <a href="index.php"><img src="images/Logo(light).png" id="logo" alt="logo"></a>

            <!-- Nav buttons -->
            <div style="margin-right:20px">
                <ul>
                    <li><a href="index.php" class="active"><font class="hov">Home</font></a></li>
                    <li><a href="searchApartment.php"><font class="hov">Apartments</font></a></li>
                    <li><a href="aboutus.html"><font class="hov">About Us</font></a></li>
                    <li><a href="contactUs.php" ><font class="hov">Contact Us</font></a></li>
                </ul>
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
        </nav>
        


<div style="padding: 20px;">
  <form action="updateBuyerAcc.php"  enctype="multipart/form-data" method="POST">
  <div class="profiles" style="text-align:center">
            <div class ="profile1">
                <div id="previewDp">
                    <img src="<?php echo $dp ?>" class="profile1-img">
                </div>    
                <input type="file" name = "dp" id="dp" accept="image/*"  onchange="loadFile(event)">
                
            </div>
  </div>
    <div class="formName">
    <label for="fname">First Name</label>
    </div>
    <div class="form">
        <input type="text" id="fname" name="firstname" value='<?php echo$fname ?>'><br>
    </div>

    <div class="formName">
    <label for="lname">Last Name</label>
    </div>
    <div class="form">
    <input type="text" id="lname" name="lastname"  value='<?php echo$lname ?>'><br>
    </div>

    <div class="formName">
    <label for="email">E-mail</label>
    </div>
    <div class="form">
    <input type="text" id="email" name="mail" disabled  value='<?php echo$email ?>'><br>
    </div>

    <div class="formName">
    <label for="type">Account Type</label>
    </div>
    <div class="form">
    <input type="text" id="type" name="type" disabled  value='<?php echo$acc ?>'><br>
    </div>

    <div class="formName">
    <label for="pnumber">Phone Number</label>
    </div>
    <div class="form">
    <input type="text" id="pnumber" name="phonenumber"  value='<?php echo$phone ?>' pattern="[0-9]{10}"><br>
    </div>

    <div class="formName">
    <label for="password">Password</label>
    </div>
    <div class="form">
    <input type="password" name="pwd" id="pwd" required onkeyup="showImg('pwd','pwdIco');" value='<?php echo$pwd ?>' minlength="8">
    <div class="show" onclick="showPwd('pwd','pwdIco')"><img src="images/show.png" width="20px" height="20px" id="pwdIco"></div><br>
    </div>

    <div class="formName">
    <label for="address">Address</label>
    </div>
    <div class="form">
    <textarea name="addrs" id="addrs" cols="46" rows="2" placeholder="Optional"><?php echo $addrs ?></textarea>
    </div>

    <div class="formName">
    <input type="submit" value="Submit">
    </div>
  </form>
    <a href="deleteSeller.php"><button id="delete">Delete Account</button></a>
</div>




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
        <script src="js/loginScript.js"></script>
        <script>
            function loadFile(event){
                var dp = document.getElementById('previewDp');
                var source = URL.createObjectURL(event.target.files[0]);
                dp.innerHTML = '<img src="'+source+'" class="profile1-img">';
                    
                dp.onload = function() {
                    dp.src = "<?php echo $dp ?>";
                } 
            }
                        
                 
        </script>
    </body>
</html>