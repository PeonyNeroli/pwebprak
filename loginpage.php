<?php
// Start session
session_start();

// Check if user is already logged in
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: Homepage.html");
    exit;
}

// Check for login error
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    // Display error as JavaScript alert
    echo "<script>alert('$error_message');</script>";
    // Clear the error message
    unset($_SESSION['login_error']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob: https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://code.jquery.com https://unpkg.com https://d3js.org https://threejs.org https://cdn.plot.ly https://stackpath.bootstrapcdn.com https://maps.googleapis.com https://cdn.tailwindcss.com https://ajax.googleapis.com https://kit.fontawesome.com https://cdn.datatables.net https://maxcdn.bootstrapcdn.com https://code.highcharts.com https://tako-static-assets-production.s3.amazonaws.com https://www.youtube.com https://fonts.googleapis.com https://fonts.gstatic.com https://pfst.cf2.poecdn.net https://puc.poecdn.net https://i.imgur.com https://wikimedia.org https://*.icons8.com https://*.giphy.com https://picsum.photos https://images.unsplash.com; frame-src 'self' https://www.youtube.com https://trytako.com; child-src 'self'; manifest-src 'self'; worker-src 'self'; upgrade-insecure-requests; block-all-mixed-content;">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Arena - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#5D5CDE',
                        secondary: '#f87171',
                        blue: {
                            login: '#0037A9'
                        },
                        red: {
                            google: '#e53e3e'
                        }
                    }
                }
            }
        }
        // Check for dark mode preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
            document.documentElement.classList.add('dark');
        }
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (event.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 dark:text-white transition-colors duration-200 flex flex-col min-h-screen">
    <!-- Header with navigation -->
    <header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="asset/Logo UNAIR.png" alt="Campus Arena Logo" class="h-12 w-12 rounded-full mr-2 object-cover border-2 border-blue-600" id="header-logo">
                    <a href="#" class="text-2xl font-bold tracking-wider dark:text-white">CAMPUS ARENA                         
                    </a>
                </div>
                
                <nav class="hidden md:flex space-x-6 items-center">
                    <a href="Homepage.html" class="font-medium hover:text-primary transition">Home</a>
                    <a href="news.html" class="font-medium hover:text-primary transition">News</a>
                    <a href="video.html" class="font-medium hover:text-primary transition">Video</a>
                    <a href="schedule_results.html" class="font-medium hover:text-primary transition">Schedule &amp; Results</a>
                    <a href="loginpage.php" class="font-medium text-primary transition">Login</a>
                    <div class="relative">
                        <input type="text" placeholder="Search here" class="pl-3 pr-10 py-1 rounded-full bg-gray-200 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary text-base">
                        <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                    </div>
                </nav>
                
                <button class="md:hidden text-gray-800 dark:text-white" id="mobile-menu-button">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
            
            <!-- Mobile menu, hidden by default -->
            <div class="md:hidden hidden pt-4 pb-2" id="mobile-menu">
                <a href="Homepage.html" class="block py-2 hover:text-primary transition">Home</a>
                <a href="#" class="block py-2 hover:text-primary transition">News</a>
                <a href="#" class="block py-2 hover:text-primary transition">Video</a>
                <a href="#" class="block py-2 hover:text-primary transition">Schedule &amp; Results</a>
                <a href="#" class="block py-2 hover:text-primary transition">Profile Athletes</a>
                <a href="loginpage.php" class="block py-2 text-primary transition">Login</a>
                <div class="relative mt-2">
                    <input type="text" placeholder="Search here" class="w-full pl-3 pr-10 py-2 rounded-full bg-gray-200 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary text-base">
                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>
        </div>
    </header>
    <!-- Main content with login form -->
    <main class="flex-grow flex items-center justify-center py-10 px-4">
        <div class="w-full max-w-md">
            <h1 class="text-3xl font-bold text-center mb-8">Log in</h1>
            
            <!-- Login alert message - initially hidden -->
            <div id="login-alert" class="<?php echo (!empty($_GET['error']) || !empty($_GET['success'])) ? '' : 'hidden'; ?> mb-4 p-4 rounded-md text-center <?php echo (!empty($_GET['error'])) ? 'bg-red-100 text-red-700' : ''; ?> <?php echo (!empty($_GET['success'])) ? 'bg-green-100 text-green-700' : ''; ?>">
                <?php 
                if (!empty($_GET['error'])) {
                    echo htmlspecialchars($_GET['error']);
                } elseif (!empty($_GET['success'])) {
                    echo htmlspecialchars($_GET['success']);
                }
                ?>
            </div>
            
            <form id="login-form" action="login_process.php" method="post">
                <!-- Email Input -->
                <div class="mb-4">
                    <input type="email" id="email" name="email" placeholder="Email" class="w-full px-4 py-3 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-base shadow-sm" required>
                </div>
                
                <!-- Password Input -->
                <div class="mb-6">
                    <input type="password" id="password" name="password" placeholder="Password" class="w-full px-4 py-3 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-base shadow-sm" required>
                </div>
                
                <!-- Login Button -->
                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-login text-white font-semibold py-3 px-4 rounded-md hover:bg-blue-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Log in
                    </button>
                </div>
                
                <!-- Forgot Password Link -->
                <div class="text-center mb-8">
                    <a href="forgot_password.php" class="text-primary hover:text-blue-700 dark:hover:text-blue-400 text-sm transition duration-200">
                        Forgot Password?
                    </a>
                </div>
            </form>
            
            <!-- Divider with text -->
            <div class="flex items-center justify-center mb-8">
                <div class="border-t border-gray-300 dark:border-gray-700 flex-grow"></div>
                <span class="px-4 text-gray-500 dark:text-gray-400 text-sm">or sign in with</span>
                <div class="border-t border-gray-300 dark:border-gray-700 flex-grow"></div>
            </div>
            
            <!-- Alternative login options -->
            <div class="space-y-3">
                <!-- Create Account Button -->
                <button onclick="window.location.href = 'create_account.php';" class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 flex items-center justify-center">
                    <i class="far fa-envelope mr-2"></i>
                    Create Account
                </button>
                
                <!-- Google Login Button -->
                <button class="w-full bg-red-google text-white font-semibold py-3 px-4 rounded-md hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 flex items-center justify-center">
                    <i class="fab fa-google mr-2"></i>
                    Log in with google
                </button>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="flex flex-col items-center">
                <!-- Keep original size but add blue background circle for the logo -->
                <div class="flex items-center justify-center mb-2">
                    <div class="bg-blue-login rounded-full p-1 flex items-center justify-center">
                        <img src="asset/Logo UNAIR.png" alt="Campus Arena Logo" class="w-10 h-10 rounded-full object-cover border-2 border-white">
                    </div>
                    <div class="text-2xl font-bold tracking-wider text-white ml-2">CAMPUS ARENA</div>
                </div>
                <div class="text-sm text-gray-400 mb-6">
                    <p>Presented By:</p>
                    <p class="text-lg font-semibold">Universitas Airlangga</p>
                </div>
                <div class="flex space-x-4 mb-4">
                    <a href="#" class="hover:text-primary"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-primary"><i class="fab fa-youtube"></i></a>
                </div>
                <div class="text-xs text-gray-400">
                    <p>© 2023 Campus Arena. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        // Close mobile menu when clicking outside
        document.addEventListener('click', (event) => {
            if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
        
        // AJAX form submission
        // AJAX form submission with improved error handling
$(document).ready(function() {
    $('#login-form').submit(function(e) {
        e.preventDefault();
        
        // Show loading state
        const alertBox = $('#login-alert');
        alertBox.removeClass('hidden bg-red-100 text-red-700 bg-green-100 text-green-700')
            .addClass('bg-blue-100 text-blue-700')
            .text('Processing login...');
        
        $.ajax({
            type: 'POST',
            url: 'login_process.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log('Response received:', response);
                
                if (response.success) {
                    // Show success message
                    alertBox.removeClass('bg-blue-100 text-blue-700 bg-red-100 text-red-700')
                        .addClass('bg-green-100 text-green-700')
                        .text(response.message);
                        
                    // Redirect after short delay
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    // Show error message
                    alertBox.removeClass('bg-blue-100 text-blue-700 bg-green-100 text-green-700')
                        .addClass('bg-red-100 text-red-700')
                        .text(response.message || 'Unknown error occurred');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.log('Response Text:', xhr.responseText);
                
                let errorMessage = 'An error occurred. Please try again.';
                
                // Try to parse error response if it's JSON
                try {
                    const jsonResponse = JSON.parse(xhr.responseText);
                    if (jsonResponse && jsonResponse.message) {
                        errorMessage = jsonResponse.message;
                    }
                } catch (e) {
                    // If can't parse JSON, use response text if available
                    if (xhr.responseText && xhr.responseText.length < 100) {
                        errorMessage = xhr.responseText;
                    }
                }
                
                alertBox.removeClass('hidden bg-blue-100 text-blue-700 bg-green-100 text-green-700')
                    .addClass('bg-red-100 text-red-700')
                    .text(errorMessage);
            }
        });
    });
}););
    </script>
</body>
</html>