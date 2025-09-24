<?php
    include_once "error-handler.php";
    require_once "config.php";
?>    
<?php 

$email = $_GET['email'];
$acc = $_GET['accType'];
$id = $_GET['id'];
$url = $_GET['bkpg'];

$sqlFav = "SELECT aprtID FROM userfavs WHERE email = ? AND accType = ? AND aprtID = ?";
$stmtFav = $conn->prepare($sqlFav);
$stmtFav->bind_param("ssi", $email, $acc, $id);
$stmtFav->execute();
$favResult = $stmtFav->get_result();
if($favResult -> num_rows >0){
    while($favRow = $favResult -> fetch_assoc()){
        $favId = $favRow['aprtID'];

                $sqlDeleteLike = "DELETE FROM userfavs WHERE email = ? AND accType = ? AND aprtID = ?";
                $stmtDeleteLike = $conn->prepare($sqlDeleteLike);
                $stmtDeleteLike->bind_param("ssi", $email, $acc, $favId);
                $stmtDeleteLike->execute();

                echo "<script>
                        var linkid = " . json_encode($url) . "+'.php#Ad'+" . json_encode($id) . ";
                        window.location.replace(linkid);
                    </script>";
             
    }
}
else{
        $sqlAddLike = "INSERT INTO userfavs(email,accType,aprtID) VALUES(?, ?, ?)";

        $stmtAddLike = $conn->prepare($sqlAddLike);
        $stmtAddLike->bind_param("ssi", $email, $acc, $id);
        $stmtAddLike->execute();

        echo "<script>
                var linkid = " . json_encode($url) . "+'.php#Ad'+" . json_encode($id) . ";
                window.location.replace(linkid);
            </script>";                    
    
} 

?>