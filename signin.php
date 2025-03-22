<?php
session_start();

// Initialize variables
$error_message = ""; // To store error messages
$form_submitted = false; // Track if the form has been submitted

// Check if the form is submitted via POST and the submit button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $form_submitted = true;

    // Database connection details
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "user_auth";

    // Create a connection to the database
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data and sanitize inputs
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate that both fields are not empty
    if (!empty($email) && !empty($password)) {
        // Prepare SQL query to check if the user exists
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Bind result variables
        $stmt->bind_result($id, $username, $hashed_password);

        // Fetch the result and verify the password
        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Redirect to the dashboard after successful login
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Invalid email or password."; // Invalid credentials
        }
    } else {
        $error_message = "Please fill in all fields."; // Empty fields
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: #121212;
      color: #e0e0e0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      overflow: hidden;
    }

    /* Background Animation */
    .background-glow {
      position: absolute;
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, rgba(187, 134, 252, 0.4), transparent);
      border-radius: 50%;
      animation: glowMove 8s infinite alternate ease-in-out;
    }

    @keyframes glowMove {
      0% {
        transform: translate(-50%, -50%) translate(0, 0);
      }
      100% {
        transform: translate(-50%, -50%) translate(100px, 100px);
      }
    }

    .form-container {
      background: #1e1e1e;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.8), 0 0 15px #bb86fc;
      width: 350px;
      text-align: center;
      border: 1px solid #333;
      animation: fadeIn 1s ease-out;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.9), 0 0 20px #bb86fc;
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

    h2 {
      color: #bb86fc;
      margin-bottom: 20px;
      font-size: 24px;
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

    .input-group {
      margin-bottom: 20px;
      animation: fadeIn 0.7s ease-out;
    }

    .input-group input {
      width: 100%;
      padding: 12px;
      border: 1px solid #333;
      border-radius: 8px;
      font-size: 14px;
      background: #2c2c2c;
      color: #e0e0e0;
      outline: none;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .input-group input:focus {
      border-color: #bb86fc;
      background: #3c3c3c;
      box-shadow: 0 0 10px #bb86fc;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #bb86fc;
      color: #121212;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s;
      animation: pop 0.5s ease-out;
    }

    button:hover {
      background: #9a67ea;
      transform: translateY(-3px);
      box-shadow: 0 0 15px #bb86fc;
    }

    p {
      margin-top: 15px;
      color: #b0b0b0;
    }

    a {
      color: #bb86fc;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="background">
        <div class="glow" style="top: 10%; left: 20%;"></div>
        <div class="glow" style="top: 50%; left: 70%;"></div>
        <div class="glow" style="top: 80%; left: 40%;"></div>
    </div>

    <!-- Signin Form -->
    <div class="form-container">
        <h2>Sign In</h2>
        <form method="POST" action="signin.php">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
            <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="submit">Sign In</button>
            <?php if ($form_submitted && !empty($error_message)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>