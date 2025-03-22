<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to signin page if not logged in
  header("Location: signin.php");
  exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* General Styles */
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: #121212; /* Dark background */
      color: #e0e0e0; /* Light text */
      overflow-x: hidden;
    }

    /* Background Animation */
    .background-glow {
      position: absolute;
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, rgba(187, 134, 252, 0.4), transparent);
      border-radius: 50%;
      animation: glowMove 8s infinite alternate ease-in-out;
      z-index: -1;
    }

    @keyframes glowMove {
      0% {
        transform: translate(-50%, -50%) translate(0, 0);
      }
      100% {
        transform: translate(-50%, -50%) translate(100px, 100px);
      }
    }

    /* Header */
    header {
      background: #1e1e1e; /* Dark gray */
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.8), 0 0 15px #bb86fc; /* Purple glow */
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
    }

    header .logo {
      font-size: 24px;
      font-weight: bold;
      color: #bb86fc; /* Purple */
    }

    header .nav-links {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    header .nav-links a {
      color: #e0e0e0; /* Light text */
      text-decoration: none;
      font-size: 16px;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    header .nav-links a:hover {
      color: #bb86fc; /* Purple */
    }

    header .signout-btn {
      background: #bb86fc; /* Purple */
      border: none;
      padding: 10px 20px;
      border-radius: 25px;
      color: #121212; /* Dark text */
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.3s ease;
    }

    header .signout-btn:hover {
      background: #9a67ea; /* Darker purple */
      transform: translateY(-3px);
      box-shadow: 0 0 15px #bb86fc; /* Purple glow */
    }

    /* About Container */
    .about-container {
      max-width: 800px;
      margin: 100px auto 20px;
      padding: 20px;
      background: #1e1e1e; /* Dark gray */
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(187, 134, 252, 0.2); /* Purple glow */
      animation: fadeIn 1s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .about-container h1 {
      font-size: 32px;
      color: #bb86fc; /* Purple */
      text-align: center;
      margin-bottom: 20px;
      animation: pop 0.5s ease-out;
    }

    @keyframes pop {
      0% {
        transform: scale(0.8);
        opacity: 0;
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    .about-container p {
      font-size: 16px;
      line-height: 1.6;
      color: #e0e0e0; /* Light text */
      margin-bottom: 20px;
    }

    .about-container p strong {
      color: #bb86fc; /* Purple */
    }

    .about-container a {
      color: #bb86fc; /* Purple */
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    .about-container a:hover {
      color: #9a67ea; /* Darker purple */
    }
  </style>
</head>
<body>
  <!-- Background Glow -->
  <div class="background-glow" style="top: 30%; left: 20%;"></div>
  <div class="background-glow" style="top: 70%; left: 80%;"></div>

  <!-- Header -->
  <header>
    <div class="logo">Student Dashboard</div>
    <div class="nav-links">
      <a href="dashboard.php">Dashboard</a>
      <a href="#feedback">Feedback</a>
      <button class="signout-btn" onclick="window.location.href='signout.php'">Sign Out</button>
    </div>
  </header>

  <!-- About Container -->
  <div class="about-container">
    <h1>About This Application</h1>
    <p>
      Welcome to the <strong>Student Dashboard</strong>, a platform designed to help students manage their academic resources efficiently.
      This application allows you to upload, view, and organize PDFs, as well as access previous year question papers for various subjects.
    </p>
    <p>
      Our goal is to provide a seamless experience for students to store and retrieve study materials, making it easier to prepare for exams
      and stay organized throughout the academic year.
    </p>
    <p>
      If you have any questions or feedback, feel free to <a href="#feedback">contact us</a>. We're always here to help!
    </p>
  </div>
</body>
</html>