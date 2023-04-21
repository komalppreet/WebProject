<?php
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the form data
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $user_type = $_POST['user_type'];

  // Validate the form data
  if (empty($username) || empty($password) || empty($confirm_password)) {
    echo 'Please fill in all fields.';
  } elseif ($password != $confirm_password) {
    echo 'Passwords do not match.';
  } else {
    // Connect to the database
    $host = 'localhost:3306';
    $dbname = 'CMS';
    $user = 'root';
    $pass = '';
    $dsn = "mysql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $pass);

    // Prepare and execute the SQL statement
    if ($user_type == 'user') {
      $sql = 'INSERT INTO users (username, password) VALUES (:username, :password)';
    } else {
      $sql = 'INSERT INTO admins (username, password) VALUES (:username, :password)';
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username, 'password' => $password]);

    // Display a success message
    echo 'User registered successfully.';
  }
}
?>
