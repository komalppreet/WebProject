<?php
// Set the content type header to indicate that an image will be displayed
header('Content-Type: image/png');

// Load the image from a file on the server
$image = imagecreatefromjpeg('captcha.png');

// Output the image to the browser
imagejpeg($image);

// Free up memory by destroying the image resource
imagedestroy($image);

// Redirect to another page after 5 seconds
header("refresh:5;url=registration.html");
?>
