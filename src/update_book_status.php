<?php
    // Database connection
    function connectDatabase($host, $username, $password, $database) {
        $connection = mysqli_connect($host, $username, $password, $database);
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $connection;
    }

    define('DB_HOST', 'db'); // Update with your DB host
    define('DB_USERNAME', 'php_docker'); // Update with your DB username
    define('DB_PASSWORD', 'passwordd'); // Update with your DB password
    define('DB_NAME', 'php_docker'); // Update with your DB name

    $connect = connectDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Get the posted JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    $title = $data['title'];
    $status = $data['status'];

    // Validate input
    if (empty($title) || !in_array($status, ['approved', 'rejected'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    // Update the book status
    $query = "UPDATE Books SET status = ? WHERE title = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('ss', $status, $title);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }

    $stmt->close();
    mysqli_close($connect);
?>
