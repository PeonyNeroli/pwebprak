<?php
// Start session
session_start();

// Include database configuration
require_once 'db_config.php';

// Initialize variables
$email = $password = "";
$emailErr = $passwordErr = $generalErr = "";
$loginSuccess = false;

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to database
    $conn = connectDB();
    
    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }
    
    // If no validation errors, proceed with login
    if (empty($emailErr) && empty($passwordErr)) {
        // Prepare and execute query
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login successful
                $loginSuccess = true;
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to homepage on successful login
                header("Location: homepage.html");
                exit();
            } else {
                // Incorrect password
                $_SESSION['login_error'] = "Incorrect password";
                header("Location: loginpage.php");
                exit();
            }
        } else {
            // Email not found
            $_SESSION['login_error'] = "Email not found";
            header("Location: loginpage.php");
            exit();
        }
        
        $stmt->close();
    } else {
        // Set error message
        if (!empty($emailErr)) {
            $_SESSION['login_error'] = $emailErr;
        } else {
            $_SESSION['login_error'] = $passwordErr;
        }
        
        // Redirect back to login page
        header("Location: loginpage.php");
        exit();
    }
    
    // Close connection
    $conn->close();
} else {
    // Not a POST request, redirect to login page
    $_SESSION['login_error'] = "Invalid request method";
    header("Location: loginpage.php");
    exit();
}

// Function to sanitize form data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>