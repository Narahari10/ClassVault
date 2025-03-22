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

// Get the file ID from the URL
$fileId = $_GET['id'] ?? 0;

// Retrieve the file from the database
$stmt = $conn->prepare("SELECT file_name, file_data FROM pdf_files WHERE id = ?");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($fileName, $fileData);

if ($stmt->fetch()) {
  // Set headers to display the PDF in the browser
  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=\"$fileName\"");
  echo $fileData; // Output the PDF data
} else {
  echo "File not found.";
}

$stmt->close();
$conn->close();
?>