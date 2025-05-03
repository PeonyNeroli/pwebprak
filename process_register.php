<?php
// Start session
session_start();

// Include database configuration
require_once 'db_config.php';

// Initialize variables
$username = $email = $password = $confirmPassword = "";
$usernameErr = $emailErr = $passwordErr = $confirmPasswordErr = $generalErr = "";
$registrationSuccess = false;

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to database
    $conn = connectDB();
    
    // Validate username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
        
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $usernameErr = "Username already exists";
        }
        $stmt->close();
    }
    
    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        } else {
            // Check if email exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $emailErr = "Email already exists";
            }
            $stmt->close();
        }
    }
    
    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
        
        // Check password length
        if (strlen($password) < 6) {
            $passwordErr = "Password must be at least 6 characters";
        }
    }
    
    // Validate confirm password
    if (empty($_POST["confirmPassword"])) {
        $confirmPasswordErr = "Please confirm your password";
    } else {
        $confirmPassword = test_input($_POST["confirmPassword"]);
        
        // Check if passwords match
        if ($password != $confirmPassword) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }
    
    // If no errors, proceed with registration
    if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            $registrationSuccess = true;
            
            // Set success message in session
            $_SESSION['registration_success'] = true;
            
            // Redirect to login page
            header("Location: loginpage.php");
            exit();
        } else {
            // Registration failed
            $generalErr = "Error: " . $stmt->error;
            
            // Store error messages in session
            $_SESSION['registration_errors'] = [
                'username_err' => $usernameErr,
                'email_err' => $emailErr,
                'password_err' => $passwordErr,
                'confirm_password_err' => $confirmPasswordErr,
                'general_err' => $generalErr
            ];
            
            // Store form data in session for repopulation
            $_SESSION['form_data'] = [
                'username' => $username,
                'email' => $email
            ];
            
            // Redirect back to registration page
            header("Location: create_account.php");
            exit();
        }
        
        $stmt->close();
    } else {
        // Store error messages in session
        $_SESSION['registration_errors'] = [
            'username_err' => $usernameErr,
            'email_err' => $emailErr,
            'password_err' => $passwordErr,
            'confirm_password_err' => $confirmPasswordErr,
            'general_err' => $generalErr
        ];
        
        // Store form data in session for repopulation
        $_SESSION['form_data'] = [
            'username' => $username,
            'email' => $email
        ];
        
        // Redirect back to registration page
        header("Location: create_account.php");
        exit();
    }
    
    // Close connection
    $conn->close();
} else {
    // Not a POST request, redirect to registration page
    header("Location: create_account.php");
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