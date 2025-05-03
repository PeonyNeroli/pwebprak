<?php
// Start session
session_start();

// Initialize variables
$username = $email = "";
$usernameErr = $emailErr = $passwordErr = $confirmPasswordErr = $generalErr = "";
$registrationSuccess = false;

// Check if there's a success message in session
if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) {
    $registrationSuccess = true;
    // Clear the session variable
    unset($_SESSION['registration_success']);
}

// Check if there are error messages in session
if (isset($_SESSION['registration_errors'])) {
    $usernameErr = $_SESSION['registration_errors']['username_err'] ?? "";
    $emailErr = $_SESSION['registration_errors']['email_err'] ?? "";
    $passwordErr = $_SESSION['registration_errors']['password_err'] ?? "";
    $confirmPasswordErr = $_SESSION['registration_errors']['confirm_password_err'] ?? "";
    $generalErr = $_SESSION['registration_errors']['general_err'] ?? "";
    
    // Clear the session variable
    unset($_SESSION['registration_errors']);
}

// Check if there's form data in session
if (isset($_SESSION['form_data'])) {
    $username = $_SESSION['form_data']['username'] ?? "";
    $email = $_SESSION['form_data']['email'] ?? "";
    
    // Clear the session variable
    unset($_SESSION['form_data']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Arena - Create Account</title>
    <style>
        :root {
            --primary-color: #0047AB;
            --text-color: #333;
            --text-light: #777;
            --border-color: #ddd;
            --bg-light: #fff;
            --bg-dark: #222;
            --social-email: #4267B2;
            --social-google: #DB4437;
        }

        .dark {
            --text-color: #eee;
            --text-light: #aaa;
            --border-color: #444;
            --bg-light: #181818;
            --bg-dark: #000;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-color);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo-text {
            font-weight: bold;
            font-size: 24px;
            margin-left: 10px;
        }

        .logo-img {
            width: 50px;
            height: 50px;
        }

        .nav {
            display: flex;
            align-items: center;
        }

        @media (max-width: 768px) {
            .nav {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }
        }

        .nav-links {
            display: flex;
            list-style: none;
            flex-wrap: wrap;
            justify-content: center;
        }

        .nav-links li {
            margin: 5px 10px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-color);
        }

        .nav-links a.active {
            color: var(--primary-color);
            font-weight: bold;
        }

        .search-bar {
            margin-left: 15px;
            padding: 8px 15px;
            border-radius: 15px;
            border: none;
            background-color: #eee;
            width: 200px;
        }

        @media (max-width: 768px) {
            .search-bar {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Main Content Styles */
        .main-content {
            max-width: 400px;
            margin: 40px auto;
            text-align: center;
            padding: 0 20px;
        }

        .form-container h1 {
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background-color: var(--bg-light);
            color: var(--text-color);
            font-size: 16px;
        }

        .password-field {
            position: relative;
            width: 100%;
            margin: 10px auto;
        }

        .password-field input {
            width: 100%;
            padding-right: 40px; /* Space for the eye icon */
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .eye-icon {
            display: block;
            width: 20px;
            height: 20px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .password-toggle:hover .eye-icon {
            opacity: 1;
        }

        .eye-icon-visible {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23777777'%3E%3Cpath d='M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z'/%3E%3C/svg%3E");
        }

        .eye-icon-hidden {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23777777'%3E%3Cpath d='M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z'/%3E%3C/svg%3E");
        }

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background-color: #003c8f;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: var(--text-light);
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid var(--border-color);
            margin: 0 15px;
        }

        .social-login {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: opacity 0.2s;
        }

        .social-btn:hover {
            opacity: 0.9;
        }

        .email-login {
            background-color: var(--social-email);
        }

        .google-login {
            background-color: var(--social-google);
        }

        /* Footer Styles */
        .footer {
            background-color: var(--bg-dark);
            color: white;
            text-align: center;
            padding: 30px 0;
            margin-top: 60px;
        }

        .footer-logo {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .footer-text {
            font-size: 14px;
            color: #ccc;
        }

        /* Error message styles */
        .error-text {
            color: #ff3860;
            font-size: 14px;
            text-align: left;
            margin-top: -5px;
            margin-bottom: 5px;
        }

        /* Success message styles */
        .success-message {
            background-color: #48c774;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo-container">
            <img src="asset/Logo UNAIR.png" alt="Campus Arena Logo" class="logo-img">
            <span class="logo-text">CAMPUS ARENA</span>
        </div>
        <nav class="nav">
            <ul class="nav-links">
                <li><a href="Homepage.php">Home</a></li>
                <li><a href="news.php">News</a></li>
                <li><a href="video.php">Video</a></li>
                <li><a href="schedule_results.php">Schedule & Results</a></li>
                <li><a href="loginpage.php" class="active">Login</a></li>
            </ul>
            <input type="text" placeholder="Search here" class="search-bar">
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="form-container">
            <h1>Create Account</h1>
            
            <?php if ($registrationSuccess): ?>
            <div class="success-message">
                Registration successful! Redirecting to login page...
            </div>
            <?php endif; ?>
            
            <?php if (!empty($generalErr)): ?>
            <div class="error-text" style="margin-bottom: 15px;"><?php echo $generalErr; ?></div>
            <?php endif; ?>
            
            <form id="signupForm" method="POST" action="process_register.php">
                <div>
                    <input type="text" name="username" placeholder="Username" class="form-input" value="<?php echo $username; ?>" required>
                    <?php if (!empty($usernameErr)): ?>
                    <div class="error-text"><?php echo $usernameErr; ?></div>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email" class="form-input" value="<?php echo $email; ?>" required>
                    <?php if (!empty($emailErr)): ?>
                    <div class="error-text"><?php echo $emailErr; ?></div>
                    <?php endif; ?>
                </div>
                <div class="password-field">
                    <input type="password" name="password" placeholder="Password" class="form-input" required>
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <span class="eye-icon eye-icon-hidden"></span>
                    </button>
                    <?php if (!empty($passwordErr)): ?>
                    <div class="error-text"><?php echo $passwordErr; ?></div>
                    <?php endif; ?>
                </div>
                <div class="password-field">
                    <input type="password" name="confirmPassword" placeholder="Confirm Password" class="form-input" required>
                    <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                        <span class="eye-icon eye-icon-hidden"></span>
                    </button>
                    <?php if (!empty($confirmPasswordErr)): ?>
                    <div class="error-text"><?php echo $confirmPasswordErr; ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="submit-btn">Create Account</button>
            </form>
        </div>

        <div class="divider">or log in with</div>

        <div class="social-login">
            <a href="loginpage.php" class="social-btn email-login">
                ✉️ Log in to your account
            </a>
            <a href="#" class="social-btn google-login">
                G+ Log in with google
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-logo">CAMPUS ARENA</div>
        <div class="footer-text">
            <p>Presented By:</p>
            <p style="font-size: 18px; font-weight: 600; margin-top: 5px;">Universitas Airlangga</p>
            <p style="margin-top: 20px; font-size: 12px;">© 2023 Campus Arena. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Dark mode support
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }
        
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (event.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });

        // JavaScript to toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.password-toggle');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const eyeIcon = this.querySelector('.eye-icon');
                    
                    // Toggle password visibility
                    if (input.type === 'password') {
                        input.type = 'text';
                        eyeIcon.classList.remove('eye-icon-hidden');
                        eyeIcon.classList.add('eye-icon-visible');
                    } else {
                        input.type = 'password';
                        eyeIcon.classList.remove('eye-icon-visible');
                        eyeIcon.classList.add('eye-icon-hidden');
                    }
                });
            });
            
            // Redirect to login page after successful registration
            <?php if ($registrationSuccess): ?>
            setTimeout(function() {
                window.location.href = "loginpage.php";
            }, 2000);
            <?php endif; ?>
        });
    </script>
</body>
</html>