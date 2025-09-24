<?php
    include_once 'config.php';
    require_once 'checkAccTypeSeller.php';
?>
<!-- uploading file -->
<?php 
    // Validate and sanitize the apartment ID
    if (!isset($_GET['aprtID']) || !is_numeric($_GET['aprtID'])) {
        echo "<script>
                alert('Invalid apartment ID!');
                window.location.replace('sellerDash.php');
              </script>";
        exit();
    }

    $id = intval($_GET['aprtID']);
    
    // Verify ownership before allowing update
    $checkOwnershipSql = "SELECT sellerMail FROM apartments WHERE aprtID = ?";
    $checkStmt = $conn->prepare($checkOwnershipSql);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows == 0) {
        echo "<script>
                alert('Apartment not found!');
                window.location.replace('sellerDash.php');
              </script>";
        exit();
    }
    
    $ownerData = $checkResult->fetch_assoc();
    if ($ownerData['sellerMail'] !== $email) {
        echo "<script>
                alert('Access denied! You can only update your own apartments.');
                window.location.replace('sellerDash.php');
              </script>";
        exit();
    }

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
            WHERE aprtID = ? AND sellerMail = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiisisssiiiis", $adType, $beds, $baths, $size, $country, $city, $town, $addrs, $title, $description, $price, $nego, $id, $email);

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
