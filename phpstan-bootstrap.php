<?php
/**
 * PHPStan Bootstrap File
 * This file prevents database connection issues during static analysis
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default session variables to prevent undefined variable warnings
if (!isset($_SESSION['profile'])) {
    $_SESSION['profile'] = 'images/user.png';
}

if (!isset($_SESSION['LoginStat'])) {
    $_SESSION['LoginStat'] = false;
}

// Mock the database connection for PHPStan
if (!class_exists('MockMySQLi')) {
    class MockMySQLi {
        public $connect_error = null;
        
        public function __construct($server, $username, $password, $db) {
            // Do nothing - this is just for static analysis
        }
        
        public function query($sql) {
            return new stdClass();
        }
        
        public function prepare($sql) {
            return new stdClass();
        }
        
        public function close() {
            return true;
        }
    }
}

// Create a mock connection variable for PHPStan
$conn = new MockMySQLi('localhost', 'root', 'root', 'apartment sales system');