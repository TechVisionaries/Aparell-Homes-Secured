<?php
    session_start();
    include_once 'config.php';
?>
<!-- uploading file -->
<?php 
    if(isset($_SESSION['count'])){

    }
    else{
        $_SESSION['count'] = 1;
    }    

    $id = $_SESSION['count'];
    $target_dir = "images/Apartments/";
    $fileCount = 0;
    $target_file = [];

    if (isset($_FILES['imgFrm']) && is_array($_FILES['imgFrm']['name'])) {
    $fileCount = count($_FILES['imgFrm']['name']);
    
    for($i = 0; $i < $fileCount; $i++){
        $target_file[$i] = $target_dir . $id . "_" .basename($_FILES["imgFrm"]["name"][$i]);
        if(isset($_FILES["imgFrm"])) {
            //check if file exists
            if (file_exists($target_file[$i])) {
                echo "Sorry, file already exists.";
                error_log("File already exists: " . $target_file[$i]);
            }
            elseif (move_uploaded_file($_FILES["imgFrm"]["tmp_name"][$i],$target_file[$i])){
                continue;
            }
            else{
                echo "Error while uploading your file.";
            }
        }
        else{
            echo "File not available";
        }
    }   

    }
    else{
        echo "No files selected";
    }
     

    $_SESSION['count'] += 1;

    $adType = $_POST['type'];
    $beds = $_POST['beds'];
    $baths = $_POST['baths'];
    $size = $_POST['size'];
    $country = htmlspecialchars($_POST['country']);
    $city = htmlspecialchars($_POST['city']);
    $town = htmlspecialchars($_POST['town']);
    $addrs = htmlspecialchars($_POST['addrs']);
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $nego = isset($_POST['nego']);

    if (isset($_SESSION['Email'])) {
        $sellerMail = $_SESSION['Email'];
    } else {
        // Handle missing email (e.g., user not logged in)
        error_log("Missing 'Email' in session");
        header("Location: loginHTML.php");
        exit();
    }

    //insert values
    $sql = "INSERT INTO apartments(adType,beds,baths,size,country,city,town,addrs,title,description,price,negotiable,img1,img2,img3,approved,sellerMail) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NULL,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisssssssisssss", $adType, $beds, $baths, $size, $country, $city, $town, $addrs, $title, $description, $price, $nego, $target_file[0], $target_file[1], $target_file[2], $sellerMail);

    if($stmt->execute()){
        echo "<script>
                alert('Successfully Posted!');
                window.location.replace('pendingAprovals.php');
              </script>";
        
    }
    else{
        echo "<script>
                alert('Unsuccessfull!');
                window.location.replace('postAd.php');
              </script>";
    }


    mysqli_close($conn);
?>
