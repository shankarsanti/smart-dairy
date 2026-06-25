<?php 
session_start();
require_once "connection.php";
if (isset($_POST['senddata'])) {
    $date = trim($_POST['to-date'] ?? '');
    $fid = trim($_POST['fid'] ?? '');
    $quan = trim($_POST['quan'] ?? '');

    $date = date("Y-m-d", strtotime($date));
    $fid = mysqli_real_escape_string($conn, $fid);
    $quan = mysqli_real_escape_string($conn, $quan);
    $date = mysqli_real_escape_string($conn, $date);

    $query = "INSERT INTO record (dt, fid, quan) VALUES ('$date', '$fid', '$quan')";
    $query_run = mysqli_query($conn, $query);
    $n = mysqli_affected_rows($conn);

    if ($n == 1) {
        $_SESSION['status'] = "Record added successfully.";
    } else {
        $_SESSION['status'] = "Record not updated successfully: " . mysqli_error($conn);
    }

    header("Location:data.php");
    exit;
}
?>



































