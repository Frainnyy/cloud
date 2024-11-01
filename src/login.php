<?php
session_start();

// Connect to the database
$connection = mysqli_connect('db', 'php_docker', 'passwordd', 'php_docker');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials
    $sql = "SELECT user_id, password, role FROM Users WHERE username = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect to index page
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "Username not found.";
    }

    $stmt->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
            <h2 class="mb-6 text-2xl font-bold text-center">Login</h2>
            <?php if (isset($error_message)): ?>
                <div class="mb-4 text-red-500"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md" placeholder="Enter your username">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md" placeholder="Enter your password">
                </div>
                <button type="submit" class="w-full px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Login</button>
            </form>
            <p class="mt-4 text-sm text-center">
                Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Register here</a>.
            </p>
            <?php if (isset($_SESSION['role'])): ?>
                <div class="mt-4 text-green-600 text-center">
                    Logged in as Role: <?php echo htmlspecialchars($_SESSION['role']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
