<?php
    require_once "config.php";
    if(isset($_POST["SearchSubmitbtn"])){
        $SearchPhrase = $_POST["search"];
        $noOfRooms = $_POST["noOfRooms"];
        $noOfBaths = $_POST["noOfBaths"];
    }
?>
<?php
    session_start();

    if(isset($_SESSION['LoginStat'])){
        $logStat = $_SESSION['LoginStat'];

        if($_SESSION['LoginStat'] == true){

            $acc = $_SESSION['AccType'];
            $email = $_SESSION['Email'];
            $dp = $_SESSION['profile'];
            $buyerLoginStat = $_SESSION['BuyerSignedIn'];
            
           
        }else{
            $acc = '';
            $email = '';
        }   

    }else{
        $acc = '';
        $email = '';
    } 

    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Search | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/searchstyle.css" id="stylesheet">
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
        <!-- search bar -->
        <div class="searchArea">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <select name="type" id="type" class="selectSearch">
                    <option value="ForSell">For sell</option>
                    <option value="ForRent">For rent</option>
                </select>
                <input type="text" name="search" placeholder="search.." value="<?php if(isset($_POST["SearchSubmitbtn"])){echo htmlspecialchars($SearchPhrase, ENT_QUOTES, 'UTF-8');} ?>" class="search">
               
                <div class="dropdown">
                    <p style="margin:0px 0px ; font-size: 18px;">Filter</p>
                <div class="dropdown-content">
                        <label class="filterLabel" style="display:inline-block">No of Beds</label>
                        <select id="noOfRooms" name="noOfRooms" class="filterslect">
                            <option value="-">-</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select><br>
                        <label class="filterLabel">No of Baths</label>
                        <select id="noOfBaths" name="noOfBaths" class="filterslect">
                            <option value="-">-</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>  
                </div>  
            </div>
            <div class="sortbyArea">
            
                <select name="sortBy" id="sortBY" class="selectSortBy">
                    <option>Sort By</option>
                    <option value="Low Price">Low Price</option>
                    <option value="High Price">High Price</option>
                </select>
    
            
            </div> 
                <input name="SearchSubmitbtn" type="submit" value="Search" class="submitBtn"> 
            </form>
        </div>
        <div class="searchWeltxt">
            <h1>Property for Sale</h1>
            <p> Here are many aprtments that you can select as you want and as matched to your reqiurements</p>
            
            
        </div>
        
        
        <hr>
        <?php
            $sql = "select * from apartments where approved = '1'";
            $result = $conn->query($sql);
            $flag = True;
            if (isset($_POST["SearchSubmitbtn"])){
                $SearchPhrase = $_POST["search"];
                $noOfRooms = $_POST["noOfRooms"];
                $noOfBaths = $_POST["noOfBaths"];
                $filter = $_POST["sortBy"];
                $flag = False;
                    
                $sql2 = "select * from apartments where (title like '%{$SearchPhrase}%') AND (beds = '{$noOfRooms}') AND (baths = '{$noOfBaths}') AND  approved = '1'";
                if($noOfRooms == '-'){
                    $sql2 = "select * from apartments where title like '%{$SearchPhrase}%' AND baths = '{$noOfBaths}' AND  approved = '1'";
                    if($filter=='Low Price'){
                        $sql2 = "select * from apartments where title like '%{$SearchPhrase}%' AND baths = '{$noOfBaths}' AND  approved = '1' order by price ASC";
                    }
                    elseif($filter=='High Price'){
                        $sql2 = "select * from apartments where title like '%{$SearchPhrase}%' AND baths = '{$noOfBaths}' AND  approved = '1' order by price DESC";
                    }
                    if($noOfBaths == '-'){
                        $sql2 = "SELECT * FROM apartments WHERE title LIKE '%$SearchPhrase%' AND  approved = '1'";
                        if($filter=='Low Price'){
                            $sql2 = "select * from apartments where title like '%{$SearchPhrase}%' AND  approved = '1' order by price ASC";
                        }
                        elseif($filter=='High Price'){
                            $sql2 = "select * from apartments where title like '%{$SearchPhrase}%' AND  approved = '1' order by price DESC";
                        }
                    }
                }
                elseif($noOfBaths == '-'){
                    $sql2 = "select * from apartments where title like '%{$SearchPhrase}%' AND beds = '{$noOfRooms}' AND  approved = '1'";
                    if(isset($_POST["filterSub"])){
                        $sql2 = "select * from apartments where title like '%{$SearchPhrase}%' AND baths = '{$noOfBaths}' AND  approved = '1' order by price ASC";
                    }
                }
                $result2 = $conn->query($sql2);

                if($result2 -> num_rows>0){
                    while($row = $result2 -> fetch_assoc()){
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
                                        <button name='contact' class='contact'>Contact Seller</button>
                                    </div>
                                </div>
                                <div class='like'>
                                    <a href='likeAprt.php?id=$id &email=$email &accType=$acc &bkpg=searchApartment' style='text-decoration:none;'><p class = 'heart' id='heart$id' style='margin: 0'>&#10084;</p>
                                </div>
                            </a>";

                        if(isset($_SESSION['LoginStat'])){
                            if($logStat == true){
                            //if logged in
                                if($buyerLoginStat == true){
                                    echo "<script>document.getElementById('heart$id').style.display = 'block';</script>";

                                    // to chk if ad is liked
                                    $sqlFav = "SELECT aprtID FROM userfavs WHERE email = '$email' AND accType = '$acc' AND aprtID='$id'";
                                    $favResult = $conn -> query($sqlFav);
                
                                    while($favRow = $favResult -> fetch_assoc()){
                                        $favId = $favRow['aprtID'];

                                        if($id == $favId){ //check if already liked
                                            echo "<script>
                                                    document.getElementById('heart$id').style.color = 'red';
                                                </script>";
                                            
                                            
                                        }      
                                        else{

                                            echo "<script>
                                                    document.getElementById('heart$id').style.color = '#ccc';
                                                </script>";
                                            
                                            continue;    

                                        }  
                                    }
                                    
                                }
                            }    
                        }
                        
                    }
                }
            }
            if($flag==True){
                
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
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
                                        <button name='contact' class='contact'>Contact Seller</button>
                                    </div>
                                </div>
                                <div class='like'>
                                    <a href='likeAprt.php?id=$id &email=$email &accType=$acc &bkpg=searchApartment' style='text-decoration:none;'><p class = 'heart' id='heart$id' style='margin: 0'>&#10084;</p>
                                </div>
                            </a>";

                        if(isset($_SESSION['LoginStat'])){
                            if($logStat == true){
                            //if logged in
                                if($buyerLoginStat == true){
                                    echo "<script>document.getElementById('heart$id').style.display = 'block';</script>";

                                    // to chk if ad is liked
                                    $sqlFav = "SELECT aprtID FROM userfavs WHERE email = '$email' AND accType = '$acc' AND aprtID='$id'";
                                    $favResult = $conn -> query($sqlFav);
                
                                    while($favRow = $favResult -> fetch_assoc()){
                                        $favId = $favRow['aprtID'];

                                        if($id == $favId){ //check if already liked
                                            echo "<script>
                                                    document.getElementById('heart$id').style.color = 'red';
                                                </script>";
                                            
                                            
                                        }      
                                        else{

                                            echo "<script>
                                                    document.getElementById('heart$id').style.color = '#ccc';
                                                </script>";
                                            
                                            continue;    

                                        }  
                                    }
                                }
                                   
                            }
                        }
                             
                    }
                }
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