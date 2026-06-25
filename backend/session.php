<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_name']);
}

// Check session timeout
function checkSessionTimeout() {
    if (isLoggedIn()) {
        $current_time = time();
        $session_time = $_SESSION['last_activity'] ?? $current_time;
        
        if ($current_time - $session_time > SESSION_TIMEOUT) {
            // Session expired
            logoutUser();
            return false;
        }
        
        // Update last activity time
        $_SESSION['last_activity'] = $current_time;
        return true;
    }
    return false;
}

// Redirect to login if not authenticated
function requireLogin() {
    if (!isLoggedIn() || !checkSessionTimeout()) {
        header('Location: ../frontend/loginpage.html');
        exit;
    }
}

// Get current user info
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['admin_id'],
            'name' => $_SESSION['admin_name'],
            'email' => $_SESSION['admin_email'] ?? 'N/A',
            'role' => $_SESSION['admin_role'] ?? 'staff'
        ];
    }
    return null;
}

// Login user
function loginUser($emp_id, $emp_name, $emp_role, $emp_email = '') {
    $_SESSION['admin_id'] = $emp_id;
    $_SESSION['admin_name'] = $emp_name;
    $_SESSION['admin_role'] = $emp_role;
    $_SESSION['admin_email'] = $emp_email;
    $_SESSION['login_time'] = time();
    $_SESSION['last_activity'] = time();
}

// Logout user
function logoutUser() {
    session_unset();
    session_destroy();
}

// Get database connection
function getConnection() {
    global $conn;
    return $conn;
}

// Sanitize input
function sanitize($data) {
    $conn = getConnection();
    return $conn->real_escape_string(trim($data));
}

// Execute query and return results
function executeQuery($query) {
    $conn = getConnection();
    $result = $conn->query($query);
    
    if (!$result) {
        return [
            'success' => false,
            'error' => $conn->error,
            'data' => []
        ];
    }
    
    return [
        'success' => true,
        'error' => '',
        'data' => $result
    ];
}

// Get single row
function getRow($query) {
    $result = executeQuery($query);
    if ($result['success'] && $result['data']->num_rows > 0) {
        return $result['data']->fetch_assoc();
    }
    return null;
}

// Get all rows
function getRows($query) {
    $result = executeQuery($query);
    $rows = [];
    if ($result['success'] && $result['data']->num_rows > 0) {
        while ($row = $result['data']->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

// Insert/Update/Delete
function executeUpdate($query) {
    $conn = getConnection();
    if ($conn->query($query) === TRUE) {
        return [
            'success' => true,
            'affected_rows' => $conn->affected_rows,
            'last_id' => $conn->insert_id,
            'error' => ''
        ];
    }
    return [
        'success' => false,
        'affected_rows' => 0,
        'last_id' => 0,
        'error' => $conn->error
    ];
}

// Format date
function formatDate($date) {
    return date('d-M-Y', strtotime($date));
}

// Format currency
function formatCurrency($amount) {
    return '₹ ' . number_format($amount, 2);
}

// Check if user exists
function userExists($eid, $phno) {
    $eid = sanitize($eid);
    $phno = sanitize($phno);
    $query = "SELECT * FROM employee WHERE eid='{$eid}' AND phno='{$phno}' LIMIT 1";
    return getRow($query) !== null;
}

// Get user details
function getUserDetails($eid, $phno) {
    $eid = sanitize($eid);
    $phno = sanitize($phno);
    $query = "SELECT * FROM employee WHERE eid='{$eid}' AND phno='{$phno}' LIMIT 1";
    return getRow($query);
}

// Get dashboard statistics
function getDashboardStats() {
    $stats = [];
    
    // Total Farmers
    $farmers = getRow("SELECT COUNT(*) as count FROM farmer");
    $stats['total_farmers'] = $farmers['count'] ?? 0;
    
    // Today's milk collection (record table has columns: dt, fid, quan)
    $today_date = date('Y-m-d');
    $milk = getRow("SELECT SUM(quan) as total FROM record WHERE dt='{$today_date}'");
    $stats['today_milk'] = $milk['total'] ?? 0;
    
    // Total revenue today (bill table - sum recent bills)
    $revenue = getRow("SELECT SUM(amount) as total FROM bill LIMIT 10");
    $stats['today_revenue'] = $revenue['total'] ?? 0;
    
    // Monthly collection (using current month)
    $monthly_date = date('Y-m');
    $monthly_milk = getRow("SELECT SUM(quan) as total FROM record WHERE DATE_FORMAT(dt,'%Y-%m')='{$monthly_date}'");
    $stats['monthly_milk'] = $monthly_milk['total'] ?? 0;
    
    // Average fat rate (if column exists)
    $fat_rate = getRow("SELECT AVG(quan) as avg_fat FROM record LIMIT 1");
    $stats['avg_fat_rate'] = $fat_rate['avg_fat'] ?? 0;
    
    return $stats;
}
?>
