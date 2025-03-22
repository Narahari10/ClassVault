<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to the signin page
header("Location: signin.php");
exit();
?>