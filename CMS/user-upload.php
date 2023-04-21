<?php
// Database connection parameters
$host = "localhost:3306";
$username = "root";
$password = "";
$dbname = "CMS";

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the form data
$gameTitle = mysqli_real_escape_string($conn, $_POST['gameTitle']);
$gameThoughts = mysqli_real_escape_string($conn, $_POST['gameThoughts']);
$gameImage = $_FILES['gameImage']['name'];

// Check if a file was uploaded
if (isset($_FILES['gameImage'])) {
    $file = $_FILES['gameImage'];

    // Check if the file is an image
    if (getimagesize($file['tmp_name'])) {
        // Move the file to a uploads directory
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $targetFile);

        // Insert the data into the database
        $sql = "INSERT INTO game_uploads (title, image, thoughts) VALUES ('$gameTitle', '$targetFile', '$gameThoughts')";

        if (mysqli_query($conn, $sql)) {
            echo "Game uploaded successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "File is not an image";
    }
}

// Close the database connection
mysqli_close($conn);
?>
