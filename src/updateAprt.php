<?php
    session_start();
    include_once 'error-handler.php';
    include_once 'config.php';
    require_once 'checkAccTypeSeller.php';

    // Initialize error array
    $errors = [];
    
    // Check if form was submitted via POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['error_message'] = 'Invalid request method.';
        header("Location: sellerDash.php");
        exit();
    }

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error_message'] = 'Invalid security token. Please try again.';
        header("Location: sellerDash.php");
        exit();
    }

    // Validate and sanitize the apartment ID
    if (!isset($_POST['aprtID']) || !is_numeric($_POST['aprtID']) || $_POST['aprtID'] <= 0) {
        $_SESSION['error_message'] = 'Invalid apartment ID.';
        header("Location: sellerDash.php");
        exit();
    }

    $apartmentId = intval($_POST['aprtID']);
    
    // Verify ownership before allowing update
    $checkOwnershipSql = "SELECT sellerMail FROM apartments WHERE aprtID = ?";
    $checkStmt = $conn->prepare($checkOwnershipSql);
    
    if (!$checkStmt) {
        $_SESSION['error_message'] = 'Database error occurred.';
        header("Location: sellerDash.php");
        exit();
    }
    
    $checkStmt->bind_param("i", $apartmentId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows == 0) {
        $checkStmt->close();
        $_SESSION['error_message'] = 'Apartment not found.';
        header("Location: sellerDash.php");
        exit();
    }
    
    $ownerData = $checkResult->fetch_assoc();
    $checkStmt->close();
    
    if ($ownerData['sellerMail'] !== $email) {
        $_SESSION['error_message'] = 'Access denied! You can only update your own apartments.';
        header("Location: sellerDash.php");
        exit();
    }

    // Validate and sanitize form inputs
    $adType = isset($_POST['type']) ? trim($_POST['type']) : '';
    $beds = isset($_POST['beds']) ? intval($_POST['beds']) : 0;
    $baths = isset($_POST['baths']) ? intval($_POST['baths']) : 0;
    $size = isset($_POST['size']) ? floatval($_POST['size']) : 0;
    $country = isset($_POST['country']) ? trim(htmlspecialchars($_POST['country'], ENT_QUOTES, 'UTF-8')) : '';
    $city = isset($_POST['city']) ? trim(htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8')) : '';
    $town = isset($_POST['town']) ? trim(htmlspecialchars($_POST['town'], ENT_QUOTES, 'UTF-8')) : '';
    $addrs = isset($_POST['addrs']) ? trim(htmlspecialchars($_POST['addrs'], ENT_QUOTES, 'UTF-8')) : '';
    $title = isset($_POST['title']) ? trim(htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8')) : '';
    $description = isset($_POST['description']) ? trim(htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8')) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $nego = isset($_POST['nego']) ? 1 : 0;

    // Server-side validation
    if (!in_array($adType, ['sale', 'rent'])) {
        $errors[] = 'Please select a valid ad type (Sale or Rent).';
    }

    if ($beds < 0 || $beds > 20) {
        $errors[] = 'Number of bedrooms must be between 0 and 20.';
    }

    if ($baths < 0 || $baths > 20) {
        $errors[] = 'Number of bathrooms must be between 0 and 20.';
    }

    if ($size <= 0 || $size > 10000) {
        $errors[] = 'Size must be between 1 and 10,000 square feet.';
    }

    if (empty($country) || strlen($country) > 50) {
        $errors[] = 'Country is required and must be less than 50 characters.';
    }

    if (empty($city) || strlen($city) > 50) {
        $errors[] = 'City is required and must be less than 50 characters.';
    }

    if (empty($town) || strlen($town) > 50) {
        $errors[] = 'Town is required and must be less than 50 characters.';
    }

    if (empty($addrs) || strlen($addrs) > 500) {
        $errors[] = 'Address is required and must be less than 500 characters.';
    }

    if (empty($title) || strlen($title) > 100) {
        $errors[] = 'Title is required and must be less than 100 characters.';
    }

    if (empty($description) || strlen($description) > 1000) {
        $errors[] = 'Description is required and must be less than 1000 characters.';
    }

    if ($price < 0 || $price > 999999999) {
        $errors[] = 'Price must be between 0 and 999,999,999.';
    }

    // If there are validation errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        header("Location: editApartment.php?aprtID=" . $apartmentId);
        exit();
    }

    // Prepare the update query
    $updateSql = "UPDATE apartments 
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
                      approved = 0,
                      dateModified = NOW()
                  WHERE aprtID = ? AND sellerMail = ?";

    $updateStmt = $conn->prepare($updateSql);

    if (!$updateStmt) {
        $_SESSION['error_message'] = 'Database preparation error occurred.';
        header("Location: editApartment.php?aprtID=" . $apartmentId);
        exit();
    }

    // Bind parameters: s=string, i=integer, d=double/float
    $updateStmt->bind_param("siidsssssdiiis", 
        $adType, $beds, $baths, $size, $country, $city, $town, 
        $addrs, $title, $description, $price, $nego, $apartmentId, $email
    );

    // Execute the update
    if ($updateStmt->execute()) {
        $updateStmt->close();
        mysqli_close($conn);
        
        // Regenerate CSRF token after successful operation
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['success_message'] = 'Apartment successfully updated! Your listing is now pending approval.';
        header("Location: pendingApprovals.php");
        exit();
    } else {
        $updateStmt->close();
        mysqli_close($conn);
        
        $_SESSION['error_message'] = 'Failed to update apartment. Please try again.';
        header("Location: editApartment.php?aprtID=" . $apartmentId);
        exit();
    }

?>