<?php
    include_once 'error-handler.php';
    include_once 'config.php';
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
            SET adType = ?,
                beds = ?,
                baths = ?,
                size = ?,
                country = ?,
                city = ?,
                town = ?,
                addrs = ?,
                title = ?,
                description = ?,
                price = ?,
                negotiable = ?, 
                approved = 'NULL'
            WHERE aprtID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiissssssiii", $adType, $beds, $baths, $size, $country, $city, $town, $addrs, $title, $description, $price, $nego, $id);

    if($stmt->execute()){
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
