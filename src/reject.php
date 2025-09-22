<?php
    include_once 'error-handler.php';
    include_once 'config.php';
?>
<?php
    $id = $_GET['aprtID'];
    $sql = "UPDATE apartments 
            SET approved = '0'
            WHERE aprtID = $id";

    if(mysqli_query($conn,$sql)){
        echo "<script>
                alert('Successfully reejected!');
                window.location.replace('toApprove.php');
            </script>";
        
    }
    else{
        echo "<script>
                alert('Unsuccessfull!');
                window.location.replace('toApprove.php');
            </script>";
    }


    mysqli_close($conn);


?>