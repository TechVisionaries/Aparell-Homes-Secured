<?php
    include_once 'config.php';
    include 'includes/security-headers.php';
?>
<!-- uploading file -->
<?php 
    $id = $_GET['aprtID'];
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


    //update values
    $sql = "UPDATE apartments 
            SET adType = '$adType',
                beds = '$beds',
                baths = '$baths',
                size = '$size',
                country = '$country',
                city = '$city',
                town = '$town',
                addrs = '$addrs',
                title = '$title',
                description = '$description',
                price = '$price',
                negotiable = '$nego', 
                approved = 'NULL'
            WHERE aprtID = $id";

    if(mysqli_query($conn,$sql)){
        echo "<script>
                alert('Successfully Updated!');
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
