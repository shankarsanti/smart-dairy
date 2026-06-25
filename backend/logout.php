<?php
require_once 'session.php';

// Logout the user
logoutUser();

// Redirect to home page
header('Location: ../frontend/index.html');
exit;
?>
