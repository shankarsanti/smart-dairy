<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dry";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['savedata'])) {
  $firstname  =  mysqli_real_escape_string($conn, trim($_POST['firstname'] ?? ''));
  $ph         =  mysqli_real_escape_string($conn, trim($_POST['ph'] ?? ''));
  $vid        =  mysqli_real_escape_string($conn, trim($_POST['vid'] ?? ''));
  $milk_type  =  mysqli_real_escape_string($conn, trim($_POST['milk_type'] ?? ''));
  $min_litre  =  mysqli_real_escape_string($conn, trim($_POST['min_litre'] ?? ''));
  $animalID   =  mysqli_real_escape_string($conn, trim($_POST['animalID'] ?? ''));

  $sql = "INSERT INTO farmer (fname, ph, f_vid, milk_type, min_litre, animalID) VALUES ('$firstname','$ph','$vid','$milk_type','$min_litre','$animalID')";

  if ($conn->query($sql) === TRUE) {
    header('Location: farmers.php');
    exit;
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    exit;
  }
}

$conn->close();
?>
