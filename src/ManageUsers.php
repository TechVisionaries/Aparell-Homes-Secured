
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
                    <li><a href="messages.php" class="hover">Customer Support</a></li>
                    <li><a href="manageUsers.php" class="hover activeNav">Manage Users</a></li>
                    <li><a href="staffsettings.php" class="hover">Settings</a></li>
                </ul>
            </nav>
            <hr>
            <nav id="nav2">
                <ul>
                    <li class="userType typeActive" onclick="changeTbl('buy')" id="buyli">Buyers</li>
                    <li class="userType" onclick="changeTbl('sell')" id="sellli">Sellers</li>
                </ul>
            </nav>
            <center>
                <div id="Buyers" class="tbldiv">
                    <table class="Tbl" border=1>
                        <tr>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Phone No</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <tr class ="accNull" id="buyAccNull">
                            <td colspan = "7">No Accounts Available</td>
                        </tr>
                        <?php
                            $sql1 = "SELECT * FROM users WHERE accType = 'buyer' ORDER BY fName";
                            $result1 = $conn->query($sql1);
                    
                            if($result1->num_rows>0){
                                while($row1 = $result1->fetch_assoc()){

                                    $email = $row1['email'];
                                    $fname = $row1['fName'];
                                    $lname = $row1['lName'];
                                    $addrs = $row1['addrs'];
                                    $phone = $row1['phoneNo'];

                                    echo"<tr>
                                            <td>$email</td>  
                                            <td>$fname</td>  
                                            <td>$lname</td>  
                                            <td>$addrs</td>  
                                            <td>$phone</td> 
                                            <td><a href='editAcc.php?email=$email &accType=buyer' style='padding:5px; text-decoration:none; background-color:gray; color:black; border-radius:5px; margin:2px; width:40px;' >Edit</a></td>
                                            <td><a href='deleteAcc.php?email=$email &accType=buyer' style='padding:5px; text-decoration:none; background-color:red; color:black;border-radius:5px; margin:2px;'>Delete</a></td>                                       
                                         </tr>";
                                }
                            }
                            else{
                                echo"<script>document.getElementById('buyAccNull').style.display = 'table-row';</script>";
                            }
                            
                        ?>
                    </table>
                </div>
                <div id="Sellers" class="tbldiv">
                    <table class="Tbl" border=1>
                        <tr>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Phone No</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <tr class ="accNull" id="sellAccNull">
                            <td colspan = "7">No Accounts Available</td>
                        </tr>
                        <?php
                            $sql2 = "SELECT * FROM users WHERE accType = 'seller' ORDER BY fName";
                            $result2 = $conn->query($sql2);

                            if($result2->num_rows>0){
                                while($row2 = $result2->fetch_assoc()){
                                    
                                    $email = $row2['email'];
                                    $fname = $row2['fName'];
                                    $lname = $row2['lName'];
                                    $addrs = $row2['addrs'];
                                    $phone = $row2 ['phoneNo'];

                                    echo"<tr>
                                            <td>$email</td>  
                                            <td>$fname</td>  
                                            <td>$lname</td>  
                                            <td>$addrs</td>  
                                            <td>$phone</td>  
                                            <td><a href='editAcc.php?email=$email &accType=seller' style='padding:5px; text-decoration:none; background-color:gray; color:black; border-radius:5px; margin:2px; width:40px;'>Edit</a></td>
                                            <td><a href='deleteAcc.php?email=$email &accType=seller' style='padding:5px; text-decoration:none; background-color:red; color:black; border-radius:5px; margin:2px; width:40px;'>Delete</a></td>                                      
                                        </tr>";
                                }
                            }
                            else{
                                echo"<script>document.getElementById('sellAccNull').style.display = 'table-row';</script>";
                            }
                            
                        ?>
                    </table>
                </div>   
            </center>  
            <a href="newUser.php"><button style="width:auto; padding: 10px; border-radius: 5px; margin:20px 0px 10px 50px; margin-left:50px;">Add New User</button></a>  
            
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