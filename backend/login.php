<?php
require_once 'session.php';

$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eid = sanitize($_POST['uname'] ?? '');
    $phno = sanitize($_POST['psw'] ?? '');
    
    // Validate input
    if (empty($eid) || empty($phno)) {
        $response['message'] = 'Please enter both Employee ID and Phone Number';
        echo json_encode($response);
        exit;
    }
    
    // Check user credentials
    $user = getUserDetails($eid, $phno);
    
    if ($user) {
        // Login successful
        loginUser($user['eid'], $user['ename'], $user['designation'], $user['phno']);
        $response['success'] = true;
        $response['message'] = 'Login successful!';
        $response['redirect'] = 'dashboard.php';
        echo json_encode($response);
        exit;
    } else {
        // Login failed
        $response['message'] = 'Invalid Employee ID or Phone Number';
        echo json_encode($response);
        exit;
    }
}

// If GET request, redirect to login page
header('Location: ../frontend/loginpage.html');
exit;
?>
