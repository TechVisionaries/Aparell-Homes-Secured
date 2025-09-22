<?php
    include_once 'error-handler.php';
    include_once 'config.php';
?>
<?php
require "checkAccTypeStaff.php";

    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>My Ads | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/dashStyle.css" id="stylesheet">
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
                    <li><a href="staffDash.php" class="hover">Dashboard</a></li>
                    <li><a href="toApprove.php" class="hover">To Approve</a></li>
                    <li><a href="messages.php" class="hover activeNav">Customer Support</a></li>
                    <li><a href="manageUsers.php" class="hover">Manage Users</a></li>
                    <li><a href="staffsettings.php" class="hover">Settings</a></li>
                </ul>
            </nav>
            <hr>
            <br><br> 
            
            <center>
                <div id="Buyers" class="tbldiv">
                    <table class="Tbl" border=1>
                        <tr>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Message</th>
                        </tr>
                        <tr class ="accNull" id="buyAccNull">
                            <td colspan = "7">No Accounts Available</td>
                        </tr>
                        <?php
                            $sql1 = "SELECT * FROM contactus";
                            $result1 = $conn->query($sql1);
                    
                            if($result1->num_rows>0){
                                while($row1 = $result1->fetch_assoc()){

                                    $email = $row1['email'];
                                    $fname = $row1['firstName'];
                                    $lname = $row1['lastName'];
                                    $msg = $row1['message'];

                                    echo"<tr>
                                            <td>$email</td>  
                                            <td>$fname</td>  
                                            <td>$lname</td>  
                                            <td>$msg</td>                                        
                                         </tr>";
                                }
                            }
                            else{
                                echo"<script>document.getElementById('buyAccNull').style.display = 'table-row';</script>";
                            }
                            
                        ?>
                    </table>
                </div>
            <br><br> 
        </section> 

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
        <script>
            function changeTbl(type){
                if(type == 'buy'){
                    document.getElementById('buyli').className = "userType typeActive";
                    document.getElementById('sellli').className = "userType";
                    document.getElementById('Buyers').style.display = "block";
                    document.getElementById('Sellers').style.display = "none";
                }
                if(type == 'sell'){
                    document.getElementById('buyli').className = "userType";
                    document.getElementById('sellli').className = "userType typeActive";
                    document.getElementById('Buyers').style.display = "none";
                    document.getElementById('Sellers').style.display = "block";
                }
            }
        </script>
    </body>
</html>