<?php

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


// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the id and username parameters were passed
    if (isset($_POST['id'], $_POST['username'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        try {
            // Prepare the SQL statement to update the user's username
            $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE id = :id");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Return a success response
            http_response_code(200);
            echo 'User updated successfully';
        } catch (PDOException $e) {
            // Return an error response if there was an error updating the user
            http_response_code(500);
            echo 'Error updating user: ' . $e->getMessage();
        }
    }

    // Check if the id parameter was passed to delete a user
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        try {
            // Prepare the SQL statement to delete the user
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Return a success response
            http_response_code(200);
            echo 'User deleted successfully';
        } catch (PDOException $e) {
            // Return an error response if there was an error deleting the user
            http_response_code(500);
            echo 'Error deleting user: ' . $e->getMessage();
        }
    }
}
?>