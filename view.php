<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to signin page if not logged in
  header("Location: signin.php");
  exit();
}

// Database connection
$host = "localhost";
$dbUsername = "root";
$dbPassword = ""; // Leave blank if no password is set
$dbName = "user_auth"; // Use your existing database

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the subject from the URL
$subject = $_GET['subject'] ?? '';
$allowedSubjects = ['dm', 'befa', 'dbms', 'os', 'se'];

if (!in_array($subject, $allowedSubjects)) {
  die("Invalid subject.");
}

// Retrieve PDFs for the logged-in user
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, file_name FROM pdf_files WHERE user_id = ? AND subject = ?");
$stmt->bind_param("is", $userId, $subject);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($fileId, $fileName);

$pdfFiles = [];
while ($stmt->fetch()) {
  $pdfFiles[] = [
    'id' => $fileId,
    'name' => $fileName,
  ];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View PDFs</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* General Styles */
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #121212; /* Dark gray base */
      color: #FFFFFF; /* White text */
    }

    /* Header */
    header {
      background-color: rgba(40, 30, 60, 0.9); /* Deep purple-blue */
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
    }

    header .logo {
      font-size: 24px;
      font-weight: bold;
      color: #FF4081; /* Vibrant pink */
    }

    header .signout-btn {
      background-color: #FFEB3B; /* Bright yellow */
      border: none;
      padding: 10px 20px;
      border-radius: 25px;
      color: #121212; /* Dark gray text */
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    header .signout-btn:hover {
      background-color: #FBC02D; /* Darker yellow */
      transform: translateY(-3px);
    }

    /* Dashboard Container */
    .dashboard-container {
      background-color: rgba(40, 30, 60, 0.9); /* Deep purple-blue */
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(255, 64, 129, 0.2); /* Pink glow */
      width: 80%;
      max-width: 400px;
      margin: 100px auto 20px;
      text-align: center;
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

    /* PDF List */
    .pdf-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 20px;
    }

    .pdf-item {
      background-color: rgba(50, 40, 70, 0.85); /* Muted purple */
      padding: 15px;
      border-radius: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .pdf-item:hover {
      transform: scale(1.05);
      box-shadow: 0 0 40px rgba(255, 64, 129, 0.4); /* Stronger pink glow */
    }

    .pdf-item a {
      color: #40C4FF; /* Light blue */
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease;
    }

    .pdf-item a:hover {
      color: #00CC66; /* Green */
    }

    /* Back to Dashboard Link */
    .back-link {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #FF4081; /* Vibrant pink */
      border-radius: 25px;
      color: #121212; /* Dark gray text */
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .back-link:hover {
      background-color: #FBC02D; /* Darker yellow */
      transform: translateY(-3px);
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <h2>View PDFs for <?php echo strtoupper($subject); ?></h2>
    <div class="pdf-list">
      <?php if (empty($pdfFiles)): ?>
        <p>No PDFs uploaded yet.</p>
      <?php else: ?>
        <?php foreach ($pdfFiles as $pdfFile): ?>
          <div class="pdf-item">
            <a href="view_pdf.php?id=<?php echo $pdfFile['id']; ?>" target="_blank"><?php echo $pdfFile['name']; ?></a>
            <a href="download_pdf.php?id=<?php echo $pdfFile['id']; ?>">Download</a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
  </div>
</body>
</html>