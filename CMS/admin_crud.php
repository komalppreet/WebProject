<?php
    // Connect to database
    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "CMS";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the action is update
        if (isset($_POST['action']) && $_POST['action'] === 'update') {
            // Get the user id and new values from the POST data
            $id = $_POST['id'];
            $newUsername = $_POST['username'];

            // Prepare and execute the SQL query to update the user
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $newUsername, $id);
            $stmt->execute();
            $stmt->close();
        }
    }
    // Check if the action is delete and the id parameter is set
if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Use the id to delete the user from the database
    $sql = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // If the delete was successful, return a success message
        echo "User with id $id has been deleted.";
    } else {
        // If the delete was not successful, return an error message
        echo "Error: " . mysqli_error($conn);
    }
}

// Check if the action is add and the necessary parameters are set
if (isset($_POST['action']) && $_POST['action'] == 'add' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use the input values to add a new user to the database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // If the add was successful, return a success message
        echo "User with username $username has been added.";
    } else {
        // If the add was not successful, return an error message
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Registration</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
<body>
<style>
    /* Style for add user form */
#add-user-form input {
  margin: 5px;
  padding: 5px;
}

#add-user-form button {
  margin: 5px;
  padding: 5px;
  background-color: #4CAF50;
  color: white;
  border: none;
}

#add-user-form button:hover {
  cursor: pointer;
  background-color: #3e8e41;
}

/* Style for user list table */
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 8px;
}

th {
  background-color: #4CAF50;
  color: white;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

/* Style for edit user button */
.edit-user-btn {
  background-color: #008CBA;
  color: white;
  border: none;
  padding: 5px 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  margin: 4px 2px;
  cursor: pointer;
}

.edit-user-btn:hover {
  background-color: #006c7d;
}

/* Style for delete user button */
.delete-user-btn {
  background-color: #f44336;
  color: white;
  border: none;
  padding: 5px 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  margin: 4px 2px;
  cursor: pointer;
}

.delete-user-btn:hover {
  background-color: #cc312d;
}

</style>

	<!-- Add User Form -->
	<form id="add-user-form">
		<input type="text" name="username" placeholder="Username">
		<input type="password" name="password" placeholder="Password">
		<button type="submit">Add User</button>
	</form>

	<hr>

	<!-- User List Table -->
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Username</th>
				<th>Password</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
				// Include database connection file
				include('db.php');

				// Fetch all users from database
				$query = "SELECT * FROM users";
				$result = mysqli_query($conn, $query);

				// Loop through each user and display in table row
				while ($row = mysqli_fetch_assoc($result)) {
					echo "<tr>";
					echo "<td>{$row['id']}</td>";
					echo "<td>{$row['username']}</td>";
					echo "<td>{$row['password']}</td>";
					echo "<td>";
					echo "<button class='edit-user-btn' data-user-id='{$row['id']}' data-username='{$row['username']}'>Edit</button>";
					echo "<button class='delete-user-btn' data-user-id='{$row['id']}'>Delete</button>";
					echo "</td>";
					echo "</tr>";
				}
			?>
		</tbody>
	</table>

	<!-- AJAX Script -->
	<script>
		// Add User AJAX
		$('#add-user-form').submit(function(event) {
			event.preventDefault();
			var form = $(this);
			var formData = form.serialize();
			$.ajax({
				url: 'admin_crud.php',
				type: 'POST',
				data: formData + '&action=add',
				success: function() {
					location.reload();
				}
			});
		});

		// Edit User AJAX
		$('.edit-user-btn').click(function() {
			var userId = $(this).data('user-id');
			var username = $(this).data('username');
			var newUsername = prompt('Enter new username:', username);
			if (newUsername) {
				$.ajax({
					url: 'admin_crud.php',
					type: 'POST',
					data: 'id=' + userId + '&username=' + newUsername + '&action=update',
					success: function() {
						location.reload();
					}
				});
			}
		});

		// Delete User AJAX
		$('.delete-user-btn').click(function() {
			var userId = $(this).data('user-id');
			if (confirm('Are you sure you want to delete this user?')) {
				$.ajax({
					url: 'admin_crud.php',
					type: 'POST',
					data: 'id=' + userId + '&action=delete',
					success: function() {
						location.reload();
					}
				});
			}
		});
	</script>

</body>
</html>
