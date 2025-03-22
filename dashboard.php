<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to signin page if not logged in
  header("Location: signin.php");
  exit();
}

$username = $_SESSION['username'];

// Subjects array
$subjects = [
  "Discrete Mathematics (DM)" => "dm",
  "BEFA" => "befa",
  "DBMS" => "dbms",
  "Operating System (OS)" => "os",
  "Software Engineering (SE)" => "se"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
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
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }

    header .nav-left {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    header .nav-right {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    header .nav-left a,
    header .nav-right a {
      color: #e0e0e0; /* Light text */
      text-decoration: none;
      font-size: 16px;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    header .nav-left a:hover,
    header .nav-right a:hover {
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

    /* Dashboard Container */
    .dashboard-container {
      max-width: 1500px;
      margin: 100px auto 20px;
      padding: 20px;
    }

    /* Welcome Message */
    .welcome-message {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 30px;
      text-align: center;
      animation: pop 0.5s ease-out, colorChange 5s infinite alternate;
      color: #bb86fc; /* Purple */
      position: relative;
    }

    .welcome-message::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      width: 60px;
      height: 4px;
      background-color: #bb86fc; /* Purple */
      transform: translateX(-50%);
      animation: underline 2s infinite;
    }

    @keyframes pop {
      0% {
        transform: scale(0.5);
        opacity: 0;
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    @keyframes colorChange {
      0% {
        color: #bb86fc; /* Purple */
      }
      50% {
        color: #ff79c6; /* Pink */
      }
      100% {
        color: #bb86fc; /* Purple */
      }
    }

    @keyframes underline {
      0% {
        width: 60px;
      }
      50% {
        width: 100px;
      }
      100% {
        width: 60px;
      }
    }

    /* Subject Folders */
    .subject-folders {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .subject-card {
      background: #1e1e1e; /* Dark gray */
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(187, 134, 252, 0.2); /* Purple glow */
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      width: 200px;
      animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
      from {
        transform: translateY(-50px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .subject-card:hover {
      transform: scale(1.05);
      box-shadow: 0 0 40px rgba(187, 134, 252, 0.4); /* Stronger purple glow */
    }

    .subject-card h3 {
      font-size: 20px;
      margin-bottom: 15px;
      color: #e0e0e0; /* Light text */
    }

    .subject-card a {
      display: inline-block;
      margin: 5px;
      padding: 12px 20px;
      border-radius: 25px;
      color: #121212; /* Dark text */
      font-size: 14px;
      font-weight: bold;
      text-decoration: none;
      transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .subject-card a::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 300%;
      height: 300%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.3), transparent);
      transform: translate(-50%, -50%) scale(0);
      transition: transform 0.5s ease;
    }

    .subject-card a:hover::before {
      transform: translate(-50%, -50%) scale(1);
    }

    .upload {
      background: linear-gradient(45deg, #FF6F61, #FF3B2F); /* Coral gradient */
    }

    .upload:hover {
      transform: translateY(-3px);
      box-shadow: 0 0 20px rgba(255, 111, 97, 0.6); /* Coral glow */
    }

    .view {
      background: linear-gradient(45deg, #00B4DB, #0083B0); /* Cyan gradient */
    }

    .view:hover {
      transform: translateY(-3px);
      box-shadow: 0 0 20px rgba(0, 180, 219, 0.6); /* Cyan glow */
    }

    .papers {
      background: linear-gradient(45deg, #88B04B, #6A9A3F); /* Green gradient */
    }

    .papers:hover {
      transform: translateY(-3px);
      box-shadow: 0 0 20px rgba(136, 176, 75, 0.6); /* Green glow */
    }
  </style>
</head>
<body>
  <!-- Background Glow -->
  <div class="background-glow" style="top: 30%; left: 20%;"></div>
  <div class="background-glow" style="top: 70%; left: 80%;"></div>

  <!-- Header -->
  <header>
    <div class="nav-left">
      <a href="dashboard.php">Dashboard</a>
    </div>
    <div class="logo">CSD2C</div>
    <div class="nav-right">
      <a href="about.php">About</a>
      <a href="feedback.php">Feedback</a>
      <button class="signout-btn" onclick="window.location.href='signout.php'">Sign Out</button>
    </div>
  </header>

  <!-- Dashboard Container -->
  <div class="dashboard-container">
    <!-- Welcome Message -->
    <div class="welcome-message">
      Welcome, <?php echo htmlspecialchars($username); ?>!
    </div>

    <!-- Subject Folders -->
    <div class="subject-folders">
      <?php foreach ($subjects as $subject => $code): ?>
        <div class="subject-card">
          <h3><?php echo $subject; ?></h3>
          <a href="upload.php?subject=<?php echo $code; ?>" class="upload">Upload PDF</a>
          <a href="view.php?subject=<?php echo $code; ?>" class="view">View PDFs</a>
          <a href="papers.php?subject=<?php echo $code; ?>" class="papers">Previous Year Papers</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>