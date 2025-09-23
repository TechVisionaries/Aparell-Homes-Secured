<?php
    include_once "error-handler.php";
    require_once "config.php";
    header("Content-Security-Policy: default-src 'self'; script-src 'self' https://apis.google.com 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; navigate-to 'self'");
?>
<?php 

define('MAX_EMAIL_LENGTH', 254);
define('MAX_ACC_TYPE_LENGTH', 32);
define('MAX_URL_LENGTH', 128);

$email = '';
if (isset($_GET['email'])) {
    $tempEmail = substr($_GET['email'], 0, MAX_EMAIL_LENGTH);
    if ($validEmail = filter_var($tempEmail, FILTER_VALIDATE_EMAIL)) {
        $email = $validEmail;
    }
}

$validAccTypes = ['buyer', 'seller', 'staff'];
$acc = '';
if (isset($_GET['accType']) && in_array($_GET['accType'], $validAccTypes)) {
    $acc = substr($_GET['accType'], 0, MAX_ACC_TYPE_LENGTH);
}

$id = 0;
if (isset($_GET['id'])) {
    $tempId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($tempId !== false && $tempId > 0) {
        $id = $tempId;
    }
}

$validPages = ['searchApartment', 'favourites', 'myAds'];
$url = '';
if (isset($_GET['bkpg']) && in_array($_GET['bkpg'], $validPages)) {
    $url = substr($_GET['bkpg'], 0, MAX_URL_LENGTH);
}

if (empty($email) || empty($acc) || $id === 0 || empty($url)) {
    header('HTTP/1.1 400 Bad Request');
    exit('Invalid parameters provided');
}

$sqlFav = $conn->prepare("SELECT aprtID FROM userfavs WHERE email = ? AND accType = ? AND aprtID = ?");
$sqlFav->bind_param("ssi", $email, $acc, $id);
$sqlFav->execute();
$favResult = $sqlFav->get_result();

if($favResult && $favResult->num_rows > 0){
    while($favRow = $favResult->fetch_assoc()){
        $favId = $favRow['aprtID'];

        $sqlDeleteLike = $conn->prepare("DELETE FROM userfavs WHERE email = ? AND accType = ? AND aprtID = ?");
        $sqlDeleteLike->bind_param("ssi", $email, $acc, $id);
        $sqlDeleteLike->execute();

        echo "<script>
                var linkid = " . json_encode($url) . "+'.php#Ad'+".json_encode((string)$id).";
                window.location.replace(linkid);
            </script>";
    }
}
else{
    $sqlAddLike = $conn->prepare("INSERT INTO userfavs(email,accType,aprtID) VALUES(?,?,?)");
    $sqlAddLike->bind_param("ssi", $email, $acc, $id);
    $sqlAddLike->execute();

    echo "<script>
            var linkid = " . json_encode($url) . "+'.php#Ad'+".json_encode((string)$id).";
            window.location.replace(linkid);
        </script>";
} 

?>