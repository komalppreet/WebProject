<?php
// Start the session
session_start();

// Include the database connection file
$host = "localhost:3306";
$username = "root";
$password = "";
$dbname = "CMS";

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  exit();
}
// Retrieve the username and password values from the form
$username = $_POST['username'];
$password = $_POST['password'];



// Construct the SQL query
$sql = "SELECT * FROM users WHERE username = :username AND password = :password";

// Prepare the SQL query
$stmt = $pdo->prepare($sql);

// Bind the parameters
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);

// Execute the query
$stmt->execute();

// Check if a row is returned
if ($stmt->rowCount() == 1) {
  // Retrieve the user's details
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Set the session variables
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['user_type'] = 'user';

  // Redirect to the home page
  header('Location: user_crud.php');
  exit();
} else {
  // Redirect back to the login page with an error message
  header('Location: login.html?error=1');
  exit();
}
?>