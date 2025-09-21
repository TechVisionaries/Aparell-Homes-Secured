<?php
    include_once "config.php";
    include 'includes/security-headers.php';
?>
<?php
    session_start();

    if(isset($_SESSION['LoginStat'])){
        $logStat = $_SESSION['LoginStat'];

        if($_SESSION['LoginStat'] == true){

            $acc = $_SESSION['AccType'];
            $dp = $_SESSION['profile'];

        }   

    }
    
    $sql = "SELECT * FROM apartments WHERE approved = '1'";

    $result = $conn->query($sql);

    
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/lightstyle.css" >
    </head>

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
        <div class="home">
            <div class="pos70">
            <h2 class="Welcome">Wellcome to Aparell Homes</h2>
            <p class="welcometxt">Your new Apartment is here. Do not waste your<br> time just go and find your dream Apartment with <br>fantastic features.</p>
            <a href="searchApartment.php" class="findButton">Find An Apartment</a>
            <a href="postAd.php" class="sellButton">Sell An Apartment</a>
            </div>
        </div>

        <!-- Increasing numbers Stats -->
        <div class="stats" onmouseover="runNum();">
            <center>
                <h1>Life is changing - and housing has just caught up.</h1>
                <div class="anim"><div id="viewers">0</div><br>Viewers</div>
                <div class="anim"><div id="buyers">0</div><br>Buyers</div>
                <div class="anim"><div id="sellers">0</div><br>Verified Sellers</div>
                <div class="anim"><div id="ads">0</div><br>Reliability</div>
            </center>
        </div>

        <!-- Featured Ads -->
        <div class="ad_Area" id="adArea">
            <h1> Featured Ads</h1>
            <?php
                if($result->num_rows>0){

                    if($result->num_rows <= 3){
                        $id1 = 1;
                        $id2 = 2;
                        $id3 = 3;
                    }
                    else{
                        $id1 = rand(1,$result->num_rows);
                        $id2 = rand(1,$result->num_rows);
                        while($id1 == $id2){
                            $id2 = rand(1,$result->num_rows);
                        } 
                        $id3 = rand(1,$result->num_rows);
                        while($id3 == $id1 || $id3 == $id2){
                            $id3 = rand(1,$result->num_rows);
                        }
                    }    
                    $sqlfeatured = "SELECT * FROM apartments WHERE aprtID = '$id1' OR aprtID = '$id2' OR aprtID = '$id3'";
            
                    $result2 = $conn->query($sqlfeatured);
                
                    while($row = $result2->fetch_assoc()){
                        $id = $row['aprtID'];
                        $title = $row['title'];
                        $addrs = $row['addrs'];
                        $baths = $row['baths'];
                        $beds = $row['beds'];
                        $img = $row['img1'];
            
                        echo "<a href='viewApartment.php?apartmentID=$id'>
                                <div class='ad'>
                                    <img src='$img' alt='image' width='100%' class='adpic'>
                                    <div class='ad_description'>
                                        <h2 id='title'>$title</h2>
                                        <p id='Address'>$addrs</p>
                                        <span id='noOfBeds'>$baths Bedroom(s)</span>
                                        <span id='baths'>$baths Bathroom(s)</span>
                                    </div>
                                </div>
                            </a>";
                    }
                }
            ?>
            <br>
            <a href="searchApartment.php"><button id="moreBtn">View More</button></a>
            <br>
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