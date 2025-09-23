
<?php
    session_start();
    require_once "config.php";
?>
<?php

    $logStat = false;
    $acc = '';
    $dp = 'images/user.png';
    
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
        <title>Search | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/viewApartment.css" id="stylesheet">

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
                <?php if ($logStat === true): ?>
                <img src="<?php echo $dp ?>" height="50px" alt="profile" onmouseover="showDpNav();" onmouseout="hideDpNav();" style="border-radius:50%";>
                <div>
                    <ul id="dpNav" onmouseover="showDpNav();" onmouseout="hideDpNav();">
                        <a href="<?php echo $acc ?>Dash.php"><li style="margin-top: 35px; border-top-left-radius: 5px; border-top-right-radius: 5px;">Dashboard</li></a>
                        <a href="logout.php"><li>Log Out</li></a>
                    </ul>
                </div>
                <?php else: ?>
                    <style>#profile { display: none; }</style>
                <?php endif; ?>
            </div>

            <!-- Dark Mode toggle switch
            <div id="drkmode">
                <img src="images/night.png" alt="dark" id="dark" class="mode" width="20px" onclick="light('OFF')">
                <img src="images/day.png" alt="light" id="light" class="mode" width="20px" onclick="light('ON')">
            </div> -->

        </nav>
       
        <?php
             /* selected apartment details*/ 
            $apartmentID=$_GET["apartmentID"];

            $sql = "select * from apartments A, users U where A.sellerMail = U.email AND U.accType = 'seller' AND aprtID=?";
            $city = "";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $apartmentID);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result -> num_rows>0){
                while($row = $result->fetch_assoc()){
                    $city = $row['city'];
                    echo "<div class='viewAprtpcsArea'>
                        <img src='{$row['img1']}'>
                    </div>
                    <div class='viewdes'>
                        <p id='viewTitle'>{$row['title']}</p>
                        <p id='viewAddress'>Address :   {$row['addrs']}</p>
                        <p id='viewBeds'>Beds   {$row['beds']}</P>
                        <p id='viewBaths'>Baths   {$row['baths']}</P>
                        <br>
                        <p id='viewDescription'><h2>Description</h2></P>
                        <p id='viewDescription2'>{$row['description']}</p>
                    </div>
                    <div class='priceArea'>
                        <p id='price'>Rs. {$row['price']}</p>
                        <button onclick='showcontact();' id='viewcontactSeller'>Contact Seller</button>
                        <div id='contactno'>
                            <p class='conDetails'>{$row['sellerMail']}<br><a href='tel:0{$row['phoneNo']}' class='no'>0{$row['phoneNo']}</a></p>
                        </div>
                    </div>"
                    ;

                }
                
            }
            /* Similar aprtment details */
            $sql2 = "select * from apartments where city=? and aprtID != ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("si", $city, $apartmentID);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            
            if($result -> num_rows>0){
                echo "<center><h1>Similar Ads</h1></center><center>";
                while($row2 = $result2->fetch_assoc()){
                    echo"<a href='viewApartment.php?apartmentID={$row2['aprtID']}'>
                    <div class='ad'>
                        <img src='{$row2['img1']}' alt='image' width='100%' class='adpic'>
                        <div class='ad_description'>
                            <h2 id='title'>{$row2['title']}</h2>
                            <p id='Address'>{$row2['addrs']}</p>
                            <span id='noOfBeds'>{$row2['beds']} Bedroom(s)</span>
                            <span id='baths'>{$row2['baths']} Bathroom(s)</span>
                        </div>
                    </div>
                </a>";
                }
                echo"</center>";
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
<script>
    function showcontact(){
        document.getElementById('contactno').style.display="block";
}
</script>