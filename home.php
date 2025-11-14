<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Professional fonts) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            overflow-y: scroll;
            overflow-x: scroll;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        /* Header styling */
        header {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        nav {
            display: flex;
            gap: 15px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #555;
        }

        /* Hero Section styling */
        .hero-section {
            position: relative;
            width: 100%;
            height: 100vh;
            /* Full viewport height */
            overflow: hidden;
            text-align: center;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Vertically center the content */
        }

        #video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Center the video in the middle */
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ensures the video covers the screen */
            z-index: -1;
            /* Places the video behind the content */
        }

        #message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 10;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background */
            border-radius: 10px;
            max-width: 80%;
            opacity: 0;
            animation: fadeIn 1.5s ease-in-out forwards;
            /* Fade-in animation */
        }

        /* Keyframe for the fade-in animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.8);
            }

            100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        #message h1 {
            font-size: 3rem;
            font-weight: 600;
        }

        #message p {
            font-size: 1.25rem;
            font-weight: 300;
        }

        .btn-primary {
            font-size: 1.1rem;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Feature section styling */
        .feature {
            background-color: #f8f9fa;
            padding: 80px 0;
            text-align: center;
        }

        .feature h2 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 50px;
        }

        .feature h3 {
            font-size: 1.8rem;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .feature p {
            font-size: 1rem;
            font-weight: 300;
        }

        .feature .row .col-md-4 {
            margin-bottom: 30px;
        }

        /* Footer styling */
        footer {
            background-color: #333;
            color: white;
            padding: 20px 0;
        }

        footer p {
            margin: 0;
            font-size: 1rem;
            font-weight: 300;
        }

        header img {
            width: 60px;
            height: 50px;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Header with navigation -->
    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <img src="logo.png" alt="logo">
            <h1>agroconnect</h1>
            <nav>
                <a href="login.php" class="text-white me-3">Login</a>
                <a href="register.php" class="text-white">Register</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="welcome">
            <!-- Video Element -->
            <video id="video" preload="auto" autoplay muted playsinline loop>
                <source src="video.mp4" type="video/mp4">
            </video>
            <!-- Message overlay on the video -->
            <div id="message">
                <h1>Welcome to the Marketplace</h1>
                <p class="lead">Buy and sell products with ease</p>
                <a href="login.php" class="btn btn-primary btn-lg">Get Started</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="feature">
        <div class="container">
            <h2>Features</h2>
            <div class="row">
                <div class="col-md-4">
                    <h3>Sell Products</h3>
                    <p>As a seller, you can easily upload your products and reach a large audience.</p>
                </div>
                <div class="col-md-4">
                    <h3>Secure Transactions</h3>
                    <p>All transactions are secured to ensure a safe buying experience.</p>
                </div>
                <div class="col-md-4">
                    <h3>Wide Variety</h3>
                    <p>Find a wide range of products across various categories to suit your needs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <p>&copy; 2025 Marketplace. All Rights Reserved.</p>
    </footer>
</body>

</html>