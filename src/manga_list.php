<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Check if user role is admin
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: approval.php"); // Redirect to approval page for admin
        exit();
    }

    include 'navbar_after.php'; // For logged-in users
    // Display the user_id and role safely
    $user_id = $_SESSION['user_id'];
    $role = isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'User'; // Default to 'User' if role is not set
    // echo "<div class='bg-green-100 p-4 text-green-800'>Logged in as User ID: $user_id | Role: $role</div>";
} else {
    include 'navbar.php'; // For guests
}

function connectDatabase($host, $username, $password, $database)
{
    $connection = mysqli_connect($host, $username, $password, $database);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $connection;
}

// ค่าคงที่สำหรับฐานข้อมูล
define('DB_HOST', 'readnet-db.cwbexon7radv.us-east-1.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'Password123!');
define('DB_NAME', 'php_docker');

// เชื่อมต่อฐานข้อมูล
$connect = connectDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Modify the query to include book_id
$query = "SELECT book_id, title, image_url FROM Books WHERE status = 'approved' ORDER BY title COLLATE utf8mb4_unicode_ci";

$response = mysqli_query($connect, $query);

// Check if the query was successful
if (!$response) {
    die("Query failed: " . mysqli_error($connect)); // Error handling
}

// เก็บรายการหนังสือทั้งหมดในอาร์เรย์
$bookList = [];
while ($book = mysqli_fetch_assoc($response)) {
    $firstChar = mb_substr($book['title'], 0, 1); // รับตัวอักษรแรกของชื่อหนังสือ
    if (!isset($bookList[$firstChar])) {
        $bookList[$firstChar] = []; // สร้างหมวดหมู่ใหม่ถ้ายังไม่มี
    }
    $bookList[$firstChar][] = $book;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Manga List</title>
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="container mx-auto px-4 py-16">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-8">Manga List</h1>

        <!-- Check if there are books to display -->
        <?php if (empty($bookList)): ?>
            <p class="text-center text-lg">No books available.</p>
        <?php else: ?>
            <!-- แสดงผลรายการหนังสือ -->
            <?php foreach ($bookList as $firstChar => $books): ?>
                <h2 class="text-2xl font-bold text-blue-700 mt-8"><?php echo htmlspecialchars($firstChar); ?></h2>
                <ul class="space-y-4">
                    <?php foreach ($books as $book): ?>
                        <li class="flex items-center p-4 bg-white rounded shadow-sm hover:bg-blue-50 transition duration-200">
                            <img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="w-16 h-20 rounded mr-4">
                            <a href="book_details.php?id=<?php echo urlencode($book['book_id']); ?>" class="block text-lg font-medium text-blue-900 hover:underline">
                                <?php echo htmlspecialchars($book['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>
