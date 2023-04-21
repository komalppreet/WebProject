<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<nav class="navbar navbar-expand-sm bg-secondary navbar-dark">
    <div class="container-fluid">
      <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link disabled" href="#" style="color: chocolate; font-size: larger;"><b>Game Zone</b></a>
          </li>
        <li class="nav-item">
          <a class="nav-link active" href="registration.html">Registration</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.html">Login</a>
        </li>


      </ul>
    </div>
  </nav>
<?php
// Connect to the database
$host = "localhost:3306";
$username = "root";
$password = "";
$dbname = "cms";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Handle CRUD operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create operation
    if (isset($_POST["gameTitle"]) && isset($_POST["gameThoughts"])) {
        $title = $_POST["gameTitle"];
        $image = $_POST["gameImage"];
        $thoughts = $_POST["gameThoughts"];

        $stmt = $pdo->prepare("INSERT INTO game_uploads (title, thoughts) VALUES (:title, :thoughts)");
        $stmt->execute(array(':title' => $title, ':thoughts' => $thoughts));

        
    }

    // Update operation
    if (isset($_POST["updateGameId"]) && isset($_POST["updateGameTitle"]) && isset($_POST["updateGameImage"]) && isset($_POST["updateGameThoughts"])) {
        $id = $_POST["updateGameId"];
        $title = $_POST["updateGameTitle"];
        $image = $_POST["updateGameImage"];
        $thoughts = $_POST["updateGameThoughts"];

        $stmt = $pdo->prepare("UPDATE game_uploads SET title = :title, image = :image, thoughts = :thoughts WHERE id = :id");
        $stmt->execute(array(':title' => $title, ':image' => $image, ':thoughts' => $thoughts, ':id' => $id));
    }

    // Delete operation
    if (isset($_POST["deleteGameId"])) {
        $id = $_POST["deleteGameId"];

        $stmt = $pdo->prepare("DELETE FROM game_uploads WHERE id = :id");
        $stmt->execute(array(':id' => $id));
    }
}

// Read operation
$stmt = $pdo->prepare("SELECT * FROM game_uploads");
$stmt->execute();
$games = $stmt->fetchAll();
?>

<html>
<head>
    <title>Game Uploads</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id="header">
        <h1>Game Uploads</h1>
    </div>
    <div id="main">
        <div id="upload">
            <h2>Upload a Game</h2>
            <form enctype="multipart/form-data" method="POST">
                <label for="gameTitle">Game Title:</label>
                <input type="text" id="gameTitle" name="gameTitle" required><br><br>
                <label for="gameImage">Game Image:</label>
                <input type="file" id="gameImage" name="gameImage" accept="image/*" required><br><br>
                <label for="gameThoughts">Your Thoughts:</label>
                <textarea id="gameThoughts" name="gameThoughts" required></textarea><br><br>
                <input type="submit" value="Upload">
            </form>
        </div>

<div id="games">
        <h2>Games</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Image</th>
                <th>Thoughts</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($games as $game) { ?>
                <tr>
                    <td><?php echo $game["title"]; ?></td>
                    <td><img src="<?php echo $game["image"]; ?>"width="100"></td>
                    <td><?php echo $game["thoughts"]; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="updateGameId" value="<?php echo $game["id"]; ?>">
                            <button type="submit" name="action" value="edit">Edit</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="deleteGameId" value="<?php echo $game["id"]; ?>">
                            <button type="submit" name="action" value="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>