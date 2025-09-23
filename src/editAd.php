<?php
    include_once "error-handler.php";
    include_once "config.php";
?>
<?php
require "checkAccTypeSeller.php";

    $id = $_GET['aprtID'];
    $sql = "SELECT * FROM apartments WHERE aprtID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $adType = $row['adType'];
    $beds = $row['beds'];
    $baths = $row['baths'];
    $size = $row['size'];
    $country = $row['country'];
    $city = $row['city'];
    $town = $row['town'];
    $addrs = $row['addrs'];
    $title = $row['title'];
    $description = $row['description'];
    $price = $row['price'];
    $nego = $row['negotiable'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Post Ad | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/postAdStyle.css" id="stylesheet">
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

        <br>

        <!-- Post Ad form -->
        <div id="form">
            <form action='updateAprt.php?<?php echo"aprtID=$id" ?>' method="post" id="aprtForm">    
            <h1>Post Ad</h1>

                <!-- Ad ID -->
                <label>ID </label>
                <input type="text" id="id" name="id" value='<?php echo $_GET["aprtID"] ?>' disabled>

                <br>

                <!-- Ad Type -->
                <label>For: </label>
                <input type="radio" id="sale" name="type" value="sale" ><label for="sale">Sale</label>
                <input type="radio" id="rent" name="type" value="rent" ><label for="rent">Rent</label>

                <br>

                <!-- No. of Bedrooms -->
                <div id="bedDiv">
                    <label for="beds">Bedrooms</label><br>
                    <input type="number" name="beds" id="beds" value="<?php echo "$beds" ?>" required>
                </div>

                <!-- No. of Bathrooms -->
                <div id="bathDiv">
                    <label for="baths">Bathrooms</label><br>
                    <input type="number" name="baths" id="baths" value="<?php echo "$baths" ?>" required>
                </div>

                <br>

                <!-- Apartmnet Size -->
                <label for="size">Size</label><br>
                <input type="number" name="size" id="size" placeholder="(sqrft)" value="<?php echo "$size" ?>" required>

                <br>

                <!-- Location -->
                <label for="country">Location</label><br>
                <input type="text" name="country" id="country" placeholder="Country" value="<?php echo "$country" ?>" required>
                <input type="text" name="city" id="city" placeholder="City" value="<?php echo "$city" ?>" required>
                <input type="text" name="town" id="town" placeholder="Town" value="<?php echo "$town" ?>" required>
                
                <br>

                <!-- Address -->
                <label for="addrs">Address</label><br>
                <textarea name="addrs" id="addrs" cols="30" rows="4" required><?php echo "$addrs" ?></textarea>
                
                <br>

                <!-- Apartmnet Title -->
                <label for="title">Title</label><br>
                <input type="text" name="title" id="title" value="<?php echo "$title" ?>" required>
                
                <br>

                <!-- Description -->
                <label for="description">Description</label><br>
                <textarea name="description" id="description" cols="50" rows="6" required><?php echo "$description" ?></textarea>

                <br>

                <!-- Price -->
                <label for="price">Price (Rs)</label><br>
                <input type="number" name="price" id="price" value="<?php echo "$price" ?>" required>

                <br>

                <!-- Negotiable -->
                <input type="checkbox" name="nego" id="nego"><label for="nego">Negotiable</label>
                
                
                <br>

                <hr>

                <center>
                    <!-- Submit Button -->
                    <button id="submitBtn">Update Ad</button>
                </center>
            </form>
        </div>

        <br><br><br><br>

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
        <!-- preview image -->
        <script>
            document.getElementById("<?php echo $adType ?>").checked = true;
            if(<?php echo "$nego" ?>){
                document.getElementById("nego").checked = true;
            }

            function loadFile(event){
                var output = document.getElementById('output');
                output.innerHTML = "";
                var source = [];
                var filesCount = event.target.files.length;
                if(filesCount > 3){
                    alert("Only 3 files are allowed to upload");
                    document.getElementById("imgFrm").value = "";
                    output.innerHTML = "";
                }
                else{
                    for(var i = 0; i < filesCount; i++){
                        source[i] = URL.createObjectURL(event.target.files[i]);
                        output.innerHTML +=  ("<img src = '"+source[i]+"' width='100px' height='100px' style='margin:20px;'>");
                    }
                    output.onload = function() {
                        output.innerHTML = "";
                    } 
                }
                        
            }      
        </script>
    </body>
</html>