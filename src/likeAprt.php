<?php
    include_once "error-handler.php";
    require_once "config.php";
?>    
<?php 

$email = $_GET['email'];
$acc = $_GET['accType'];
$id = $_GET['id'];
$url = $_GET['bkpg'];

$sqlFav = "SELECT aprtID FROM userfavs WHERE email = '$email' AND accType = '$acc' AND aprtID = '$id'";
$favResult = $conn -> query($sqlFav);
if($favResult -> num_rows >0){
    while($favRow = $favResult -> fetch_assoc()){
        $favId = $favRow['aprtID'];
    
                $sqlDeleteLike = "DELETE FROM userfavs WHERE email = '$email' AND accType = '$acc' AND aprtID = $id";
    
                mysqli_query($conn,$sqlDeleteLike);
    
                echo "<script>
                        var linkid = " . json_encode($url) . "+'.php#Ad'+$id;
                        window.location.replace(linkid);
                    </script>";
             
    }
}
else{
        $sqlAddLike = "INSERT INTO userfavs(email,accType,aprtID) VALUES('$email','$acc','$id')";

        mysqli_query($conn,$sqlAddLike);

        echo "<script>
                var linkid = " . json_encode($url) . "+'.php#Ad'+$id;
                window.location.replace(linkid);
            </script>";                    
    
} 

?>