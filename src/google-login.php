<?php
session_start();
include_once 'config.php';

// Google OAuth configuration
$client_id = '283315803916-nedouh9bp5kiekkepefvhvtfg5ivatm0.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'http://localhost/Aparell-Homes-Secured/src/google-login.php';

if (!isset($_GET['code']) && isset($_GET['accType'])) {
    $validAccTypes = ['buyer', 'seller', 'staff'];
    $accTypeParam = $_GET['accType'];
    
    if (in_array($accTypeParam, $validAccTypes)) {
        $_SESSION['google_signup_accType'] = $accTypeParam;
    } else {
        $_SESSION['google_signup_accType'] = 'buyer'; // Default
    }
}


// Check if we have an authorization code
if (isset($_GET['code'])) {

    $accType = $_SESSION['google_signup_accType'] ?? 'buyer';
    
    unset($_SESSION['google_signup_accType']);

    $token_url = 'https://oauth2.googleapis.com/token';
    
    $post_fields = [
        'code' => $_GET['code'],
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    ];
    
    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $tokens = json_decode($response, true);
    
    if (isset($tokens['access_token'])) {
        $user_info = json_decode(file_get_contents(
            "https://www.googleapis.com/oauth2/v3/userinfo?access_token=" . $tokens['access_token']
        ), true);
        

        $email = $user_info['email'];
        $name = $user_info['name'] ?? 'Google User';
        $given_name = $user_info['given_name'] ?? 'Google';
        $family_name = $user_info['family_name'] ?? 'User';
        $picture = $user_info['picture'] ?? 'images/user.png';
        $phoneNo = 94;
        // Check if user exists in our database
        $check_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check_user->bind_param("s", $email);
        $check_user->execute();
        $result = $check_user->get_result();
        $address = " ";
        $phoneNo = 94;

        if ($result->num_rows == 0) {

            $password = "_";
            
            $insert = $conn->prepare("INSERT INTO users (email, fName, lName, addrs, accType, phoneNo, password, profile) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param("ssssssss", $email, $given_name, $family_name, $address, $accType, $phoneNo, $password, $picture);
            $insert->execute();
            
            $user_id = $conn->insert_id;
        } else {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];
            $accType = $user['accType'];
        }
        

        $_SESSION['Email'] = $email;
        $_SESSION['Pwd'] = '';
        $_SESSION['AccType'] = $accType;
        $_SESSION['fName'] = $given_name;
        $_SESSION['lName'] = $family_name;
        $_SESSION['profile'] = $picture;
        $_SESSION['LoginStat'] = true;
        $_SESSION['addrs'] = $address;
        $_SESSION['mobile'] = $phoneNo;
        
        // Set account-specific session variables
        if ($accType == 'buyer') {
            $_SESSION['BuyerSignedIn'] = true;
            header("Location: buyerDash.php");
        } elseif ($accType == 'seller') {
            $_SESSION['SellerSignedIn'] = true;
            header("Location: sellerDash.php");
        } elseif ($accType == 'staff') {
            $_SESSION['StaffSignedIn'] = true;
            header("Location: staffDash.php");
        }
        exit();
    } else {
        // Handle token exchange error
        error_log("Google OAuth token exchange failed: " . print_r($tokens, true));
        header("Location: loginHTML.php?error=oauth");
        exit();
    }
} else {
    $auth_url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'response_type' => 'code',
        'scope' => 'openid email profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ]);
    
    header("Location: $auth_url");
    exit();
}
?>