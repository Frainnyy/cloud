<?php
session_start();

include 'navbar_after.php';

$connection = mysqli_connect('readnet-db.cwbexon7radv.us-east-1.rds.amazonaws.com', 'admin', 'Password123!', 'php_docker');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, password FROM Users WHERE user_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = htmlspecialchars($user['username']);
    $password = '********';
} else {
    echo "User not found.";
    exit();
}
$stmt->close();

// Retrieve books created by the user
$sql_books = "SELECT title, author, image_url FROM Books WHERE created_by = ?";
$stmt_books = $connection->prepare($sql_books);
$stmt_books->bind_param("i", $user_id);
$stmt_books->execute();
$books_result = $stmt_books->get_result();
$books = [];

if ($books_result->num_rows > 0) {
    while ($book = $books_result->fetch_assoc()) {
        $books[] = $book;
    }
}

$stmt_books->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white font-sans">

<!-- Profile Content -->
<div class="container mx-auto py-10 px-4 flex justify-center">
  <div class="bg-white text-black p-8 rounded-lg shadow-lg w-full max-w-md">
    <div class="flex items-center justify-center mb-4">
      <img src="https://i.pinimg.com/originals/f1/0f/f7/f10ff70a7155e5ab666bcdd1b45b726d.jpg" alt="Profile Image" class="rounded-full w-20 h-20">
    </div>
    <h2 class="text-center font-bold text-xl mb-4">
      Welcome, <?php echo $username; ?> 
      <button onclick="toggleEditMode()" class="text-gray-500 hover:text-gray-700 ml-2">
        <img src="https://cdn-icons-png.flaticon.com/128/10573/10573603.png" alt="Edit Icon" class="inline w-5 h-5">
      </button>
    </h2>

    <form id="profileForm">
      <label class="block text-sm font-medium mb-1">Username:</label>
      <input id="username" type="text" value="<?php echo $username; ?>" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 bg-gray-100" disabled>

      <label class="block text-sm font-medium mb-1">Password:</label>
      <input id="password" type="text" value="<?php echo $password; ?>" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 bg-gray-100" disabled>

      <button id="saveButton" type="button" onclick="saveChanges()" class="hidden w-full bg-pink-500 text-white py-2 rounded hover:bg-pink-600">Save Changes</button>
    </form>

    <!-- Books Created by User Section -->
    <div class="mt-8">
      <h3 class="text-lg font-bold text-center">Books Created by You</h3>
      <?php if (count($books) > 0): ?>
        <div class="grid grid-cols-1 gap-4 mt-4">
          <?php foreach ($books as $book): ?>
            <div class="flex items-center bg-gray-100 rounded-lg shadow p-4">
              <img src="<?php echo htmlspecialchars($book['image_url'] ?? 'https://via.placeholder.com/100'); ?>" alt="Book Image" class="w-16 h-16 rounded mr-4">
              <div>
                <h4 class="text-md font-semibold"><?php echo htmlspecialchars($book['title']); ?></h4>
                <p class="text-sm text-gray-600">by <?php echo htmlspecialchars($book['author']); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-center mt-4">No books created by you.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

<!-- JavaScript -->
<script>
  function toggleEditMode() {
    const isDisabled = document.getElementById("username").disabled;
    document.getElementById("username").disabled = !isDisabled;
    document.getElementById("password").disabled = !isDisabled;

    document.getElementById("username").classList.toggle("bg-gray-100");
    document.getElementById("password").classList.toggle("bg-gray-100");
    document.getElementById("saveButton").classList.toggle("hidden");
  }

  function saveChanges() {
    alert("Changes saved!");
    toggleEditMode();
  }
</script>

</body>
</html>
