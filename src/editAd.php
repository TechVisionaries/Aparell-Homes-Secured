<?php
    session_start();
    include_once "error-handler.php";
    include_once "config.php";
    require "checkAccTypeSeller.php";

    // Generate CSRF token if it doesn't exist
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $error_message = '';
    $apartment_data = null;

    // Validate and sanitize the apartment ID
    if (!isset($_GET['aprtID']) || !is_numeric($_GET['aprtID']) || $_GET['aprtID'] <= 0) {
        $error_message = 'Invalid apartment ID!';
    } else {
        $id = intval($_GET['aprtID']);
        
        // Check if the apartment exists and belongs to the current seller
        $sql = "SELECT * FROM apartments WHERE aprtID = ? AND sellerMail = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("is", $id, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                $error_message = 'Access denied! You can only edit your own apartments.';
            } else {
                $apartment_data = $result->fetch_assoc();
            }
            $stmt->close();
        } else {
            $error_message = 'Database error occurred.';
        }
    }

    // If there's an error, redirect back to dashboard
    if (!empty($error_message)) {
        $_SESSION['error_message'] = $error_message;
        header("Location: sellerDash.php");
        exit();
    }

    // Extract apartment data safely
    $adType = htmlspecialchars($apartment_data['adType'], ENT_QUOTES, 'UTF-8');
    $beds = intval($apartment_data['beds']);
    $baths = intval($apartment_data['baths']);
    $size = floatval($apartment_data['size']);
    $country = htmlspecialchars($apartment_data['country'], ENT_QUOTES, 'UTF-8');
    $city = htmlspecialchars($apartment_data['city'], ENT_QUOTES, 'UTF-8');
    $town = htmlspecialchars($apartment_data['town'], ENT_QUOTES, 'UTF-8');
    $addrs = htmlspecialchars($apartment_data['addrs'], ENT_QUOTES, 'UTF-8');
    $title = htmlspecialchars($apartment_data['title'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($apartment_data['description'], ENT_QUOTES, 'UTF-8');
    $price = floatval($apartment_data['price']);
    $nego = intval($apartment_data['negotiable']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Apartment | Aparell</title>
        <link rel="icon" type="image" href="images/Favicon.png">
        <link rel="stylesheet" href="style/postAdStyle.css" id="stylesheet">
        <style>
            .error-message {
                background-color: #f8d7da;
                color: #721c24;
                padding: 15px;
                border: 1px solid #f5c6cb;
                border-radius: 5px;
                margin: 10px 0;
            }
            .form-group {
                margin-bottom: 15px;
            }
            .inline-group {
                display: inline-block;
                margin-right: 20px;
            }
            input[type="number"], input[type="text"], textarea {
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
                width: 100%;
                box-sizing: border-box;
            }
            .location-group input {
                width: 30%;
                display: inline-block;
                margin-right: 2%;
            }
        </style>
    </head>

    <body>
        <!-- Navigation panel -->
        <nav>
            <!-- Logo -->
            <a href="index.php"><img src="images/Logo(light).png" id="logo" alt="Aparell Logo"></a>

            <!-- Nav buttons -->
            <ul>
                <li><a href="index.php"><span class="hov">Home</span></a></li>
                <li><a href="searchApartment.php"><span class="hov">Apartments</span></a></li>
                <li><a href="aboutus.html"><span class="hov">About Us</span></a></li>
                <li><a href="contactUs.php"><span class="hov">Contact Us</span></a></li>
            </ul>

            <!-- Profile icon -->
            <div id="profile">
                <img src="<?php echo htmlspecialchars($dp, ENT_QUOTES, 'UTF-8'); ?>" height="50" alt="profile" onmouseover="showDpNav();" onmouseout="hideDpNav();" style="border-radius:50%">
                <div>
                    <ul id="dpNav" onmouseover="showDpNav();" onmouseout="hideDpNav();">
                        <a href="<?php echo htmlspecialchars($acc, ENT_QUOTES, 'UTF-8'); ?>Dash.php">
                            <li style="margin-top: 35px; border-top-left-radius: 5px; border-top-right-radius: 5px;">Dashboard</li>
                        </a>
                        <a href="logout.php"><li>Log Out</li></a>
                    </ul>
                </div>
            </div>
        </nav>

        <br>

        <!-- Edit Apartment form -->
        <div id="form">
            <form action="updateAprt.php" method="post" id="aprtForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="aprtID" value="<?php echo $id; ?>">
                
                <h1>Edit Apartment</h1>

                <!-- Display any error messages -->
                <?php if (isset($_SESSION['form_errors'])): ?>
                    <div class="error-message">
                        <?php 
                        echo implode('<br>', $_SESSION['form_errors']); 
                        unset($_SESSION['form_errors']);
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Ad ID (Display only) -->
                <div class="form-group">
                    <label for="display_id">Apartment ID:</label>
                    <input type="text" id="display_id" value="<?php echo $id; ?>" disabled>
                </div>

                <!-- Ad Type -->
                <div class="form-group">
                    <label>For:</label><br>
                    <div class="inline-group">
                        <input type="radio" id="sale" name="type" value="sale" <?php echo ($adType === 'sale') ? 'checked' : ''; ?>>
                        <label for="sale">Sale</label>
                    </div>
                    <div class="inline-group">
                        <input type="radio" id="rent" name="type" value="rent" <?php echo ($adType === 'rent') ? 'checked' : ''; ?>>
                        <label for="rent">Rent</label>
                    </div>
                </div>

                <!-- Bedrooms and Bathrooms -->
                <div class="form-group">
                    <div id="bedDiv" class="inline-group">
                        <label for="beds">Bedrooms:</label><br>
                        <input type="number" name="beds" id="beds" value="<?php echo $beds; ?>" min="0" max="20" required>
                    </div>

                    <div id="bathDiv" class="inline-group">
                        <label for="baths">Bathrooms:</label><br>
                        <input type="number" name="baths" id="baths" value="<?php echo $baths; ?>" min="0" max="20" required>
                    </div>
                </div>

                <!-- Apartment Size -->
                <div class="form-group">
                    <label for="size">Size (sqft):</label><br>
                    <input type="number" name="size" id="size" value="<?php echo $size; ?>" min="1" max="10000" step="0.01" required>
                </div>

                <!-- Location -->
                <div class="form-group location-group">
                    <label>Location:</label><br>
                    <input type="text" name="country" id="country" placeholder="Country" value="<?php echo $country; ?>" maxlength="50" required>
                    <input type="text" name="city" id="city" placeholder="City" value="<?php echo $city; ?>" maxlength="50" required>
                    <input type="text" name="town" id="town" placeholder="Town" value="<?php echo $town; ?>" maxlength="50" required>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="addrs">Address:</label><br>
                    <textarea name="addrs" id="addrs" cols="30" rows="4" maxlength="500" required><?php echo $addrs; ?></textarea>
                </div>

                <!-- Apartment Title -->
                <div class="form-group">
                    <label for="title">Title:</label><br>
                    <input type="text" name="title" id="title" value="<?php echo $title; ?>" maxlength="100" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description:</label><br>
                    <textarea name="description" id="description" cols="50" rows="6" maxlength="1000" required><?php echo $description; ?></textarea>
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label for="price">Price (Rs):</label><br>
                    <input type="number" name="price" id="price" value="<?php echo $price; ?>" min="0" max="999999999" step="0.01" required>
                </div>

                <!-- Negotiable -->
                <div class="form-group">
                    <input type="checkbox" name="nego" id="nego" value="1" <?php echo ($nego) ? 'checked' : ''; ?>>
                    <label for="nego">Negotiable</label>
                </div>

                <hr>

                <center>
                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn">Update Apartment</button>
                    <button type="button" onclick="window.location.href='sellerDash.php'" style="margin-left: 10px; background: #6c757d;">Cancel</button>
                </center>
            </form>
        </div>

        <br><br><br><br>

        <!-- Footer -->
        <footer>
            <center>
                <!-- Social Media -->
                <div id="icons">
                    <a href="https://www.facebook.com/profile.php?id=100086685492601" target="_blank" rel="noopener">
                        <img src="images/facebook.png" width="30" alt="Facebook">
                    </a>
                    <a href="https://www.instagram.com/aparellhomes/" target="_blank" rel="noopener">
                        <img src="images/instagram.png" width="30" alt="Instagram">
                    </a>
                    <a href="https://twitter.com/AparellHomes" target="_blank" rel="noopener">
                        <img src="images/twitter.png" width="30" alt="Twitter">
                    </a>
                    <a href="mailto:aparellhomes@gmail.com">
                        <img src="images/mail.png" width="30" alt="Email">
                    </a>
                    <a href="https://wa.me/0740276949" target="_blank" rel="noopener">
                        <img src="images/whatsapp.png" width="30" alt="WhatsApp">
                    </a>
                </div>
                <!-- Links -->
                <div id="links">
                    <a href="aboutus.html">Info</a> &#x2022; <a href="contactUs.php">Support</a> &#x2022; <a href="contactUs.php">Marketing</a><br>
                    <a href="terms.html">Terms of Use</a> &#x2022; <a href="privacy.html">Privacy Policy</a>
                </div>
                <!-- Copyrights -->
                <div id="cpyryt">
                    &#x00A9; 2022 Aparell Homes
                </div>
            </center>
        </footer>

        <script src="js/script.js"></script>
        
        <script>
            // Form validation
            document.getElementById('aprtForm').addEventListener('submit', function(e) {
                const requiredFields = ['type', 'beds', 'baths', 'size', 'country', 'city', 'town', 'addrs', 'title', 'description', 'price'];
                let isValid = true;
                let errorMessages = [];

                // Check required fields
                requiredFields.forEach(function(fieldName) {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (!field || (field.type === 'radio' && !document.querySelector(`[name="${fieldName}"]:checked`)) || (!field.value.trim() && field.type !== 'radio')) {
                        isValid = false;
                        errorMessages.push(`${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} is required.`);
                    }
                });

                // Validate numeric fields
                const numericFields = ['beds', 'baths', 'size', 'price'];
                numericFields.forEach(function(fieldName) {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field && field.value && (isNaN(field.value) || parseFloat(field.value) < 0)) {
                        isValid = false;
                        errorMessages.push(`${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} must be a valid positive number.`);
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fix the following errors:\n' + errorMessages.join('\n'));
                }
            });

            // Character counter for textarea fields
            function addCharacterCounter(textareaId, maxLength) {
                const textarea = document.getElementById(textareaId);
                if (textarea) {
                    const counter = document.createElement('div');
                    counter.style.fontSize = '12px';
                    counter.style.color = '#666';
                    counter.style.textAlign = 'right';
                    textarea.parentNode.appendChild(counter);

                    function updateCounter() {
                        const remaining = maxLength - textarea.value.length;
                        counter.textContent = `${textarea.value.length}/${maxLength} characters`;
                        counter.style.color = remaining < 50 ? '#dc3545' : '#666';
                    }

                    textarea.addEventListener('input', updateCounter);
                    updateCounter();
                }
            }

            // Add character counters
            addCharacterCounter('addrs', 500);
            addCharacterCounter('title', 100);
            addCharacterCounter('description', 1000);
        </script>
    </body>
</html>