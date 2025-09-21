<?php
    include_once "config.php";
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
require "checkAccTypeBuyer.php";

$email = $_SESSION['Email'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/dashStyle.css" id="stylesheet">
        <style>
            .DisplayedAds{
                width:80%;
                height:200px;
                margin: 1% 10% 2% 10%;
                padding: 10px;
                background: aliceblue;
                border-radius: 5px;
            }
            .DisplayedAds:hover{
                background-color: rgba(237, 237, 237, 0.814);
                border-radius: 10px;
            }
            .pics{
                width:300px;
                height:200px;

            }
            .AdPictures{
                width:300px;
                float:left;
                margin-right: 20px;
                
            }
            .Adsdis{
                width:20%;
                float:left;
                overflow-wrap: break-word;
                
            }
            .title{
                font-size: 20px;
                font-weight:700;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                margin-top: 5px;
            }
            .price{
                border-left:1px solid black;
                margin:2px 20px ;
                padding:0px 20px;
                float:left;
            }

            .pPrice{
                margin-top:4px;
                font-size: large;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .contactbtn{
                float:left;
            }
            .contact{
                padding:10px;
                background-color:rgb(118, 204, 118);
                border:none;
                border-radius:5px;
            }

            .noOfRooms, .noOfBaths{
                margin-bottom: 10px;
            }

            .like{
                width: fit-content;
                position: absolute;
                margin-top: -260px;
                right: 182px;
            }

            .heart{
                color:#ccc;
                font-size: 25px;
                cursor:pointer;
            }
            .heart:hover{
                color:red;
            }

        </style>
    </head>

    <body>

        <!-- Navigation panel -->
        <nav>

            <!-- Logo -->
            <a href="index.php"><img src="images/Logo(light).png" id="logo" alt="logo"></a>

            <!-- Nav buttons -->
            <ul>
                <li><a href="index.php"><font class="hov">Home</font></a></li>
                <li><a href="searchApartment.php"><font class="hov">Apartments</font></a></li>
                <li><a href="aboutus.html"><font class="hov">About Us</font></a></li>
                <li><a href="contactUs.php" ><font class="hov">Contact Us</font></a></li>
            </ul>

            <!-- Profile icon -->
            <div id="profile">
                <img src="<?php echo $dp ?>" height="50px" alt="profile" style="border-radius:50%";>
            </div>

            <!-- Dark Mode toggle switch
            <div id="drkmode">
                <img src="images/night.png" alt="dark" id="dark" class="mode" width="20px" onclick="light('OFF')">
                <img src="images/day.png" alt="light" id="light" class="mode" width="20px" onclick="light('ON')">
            </div> -->

        </nav>

        <br><br><br>
        
        <!-- Ads -->
        <section>
            <nav>
                <ul>
                    <li><a href="buyerDash.php" class="hover">Dashboard</a></li>
                    <li><a href="favourites.php" class="hover activeNav">Favourites</a></li>
                    <li><a href="Buyersettings.php" class="hover">Settings</a></li>
                </ul>
            </nav>
            <hr>
            <?php
                $sql = "SELECT * FROM apartments A, userfavs B WHERE A.aprtID = B.aprtID AND B.email = '$email' AND B.accType = 'buyer'";
                $result = $conn -> query($sql);
                if($result->num_rows>0){
                    while($row = $result->fetch_assoc()){
                        $id = $row['aprtID'];

                        echo "<a href='viewApartment.php?apartmentID=$id' id='Ad$id' style='color:black; text-decoration:none;'>
                                <div class='DisplayedAds'>
                                    <div class='AdPictures'>
                                        <img src='{$row['img1']}' class='pics'>
                                    </div>
                                    <div class='Adsdis'>
                                        <p class='title'>{$row['title']}</p>
                                        <p class='address'>{$row['addrs']}</p>
                                    </div>
                                    <div class='price'>
                                        <p class='pPrice'>Rs. {$row['price']}</p>
                                        <p class='noOfBeds'>Beds {$row['beds']}</p>
                                        <p class='baths'>Baths {$row['baths']}</p>
                                    </div>
                                    <div class= 'contactbtn'>
                                        <button name='contact' class='contact' style='width:auto'>Contact Seller</button>
                                    </div>
                                </div>
                                <div class='like'>
                                    <a href='likeAprt.php?id=$id &email=$email &accType=$acc &bkpg=favourites' style='text-decoration:none;'><p class = 'heart' id='heart$id' style='margin: 0'>X</p>
                                </div>
                            </a>
                            <br>
                            <br>";
                    }
                }
                else{
                    echo "<center><p style='padding-bottom:10px; color:grey;'><i>No Ads Marked As Favourite</i></p></center>";
                }
                
            ?>  
        </section> 

        
        <br><br>

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