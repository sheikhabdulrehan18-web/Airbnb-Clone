<?php
// db.php - Database connection file
 
$host = 'localhost';
$dbname = 'rsk9_3';
$username = 'rsk9_3';
$password = '123456';
 
// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
 
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
 
