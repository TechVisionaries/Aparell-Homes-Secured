<?php
    include_once 'error-handler.php';
    include_once 'config.php';
?>
<?php 
    $id = $_GET['aprtID'];

    //DELTE values
    $sql = "DELETE FROM apartments 
            WHERE aprtID = ?";

    $sql2 = "SELECT img1, img2, img3 FROM apartments";  

    $result = $conn -> query($sql2);
    if($result->num_rows>0){
        while($row = $result -> fetch_assoc()){
            $img1 = $row['img1'];
            $img2 = $row['img2'];
            $img3 = $row['img3'];
        }
    }  

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        if($img1 != ''){
            unlink("$img1");
        }
        if($img2 != ''){
            unlink("$img2");
        }              
        if($img3 != ''){
            unlink("$img3");
        }              
        
        echo "<script>
                alert('Successfully deleted!');
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
