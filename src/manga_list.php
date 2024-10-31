<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Manga List</title>
</head>

<body class="bg-gray-100 text-gray-800">

    <?php include 'navbar.php'; ?>

    <div class="container mx-auto px-4 py-16">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-8">Manga List</h1>

        <?php
        // ค่าคงที่สำหรับการเชื่อมต่อฐานข้อมูล
        define('DB_HOST', 'db');
        define('DB_USERNAME', 'php_docker');
        define('DB_PASSWORD', 'password');
        define('DB_NAME', 'php_docker');

        // ฟังก์ชันเชื่อมต่อฐานข้อมูล
        function connectDatabase($host, $username, $password, $database)
        {
            $connection = mysqli_connect($host, $username, $password, $database);
            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }
            mysqli_set_charset($connection, 'utf8mb4');
            return $connection;
        }

        // เชื่อมต่อฐานข้อมูล
        $connect = connectDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // ดึงข้อมูลจากตาราง books
        $query = "SELECT id, title, image_url FROM books ORDER BY title COLLATE utf8mb4_unicode_ci";
        $response = mysqli_query($connect, $query);

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

        <!-- แสดงผลรายการหนังสือ -->
        <?php foreach ($bookList as $firstChar => $books): ?>
            <h2 class="text-2xl font-bold text-blue-700 mt-8"><?php echo htmlspecialchars($firstChar); ?></h2>
            <ul class="space-y-4">
                <?php foreach ($books as $book): ?>
                    <li class="flex items-center p-4 bg-white rounded shadow-sm hover:bg-blue-50 transition duration-200">
                        <img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="w-16 h-20 rounded mr-4">
                        <a href="book_details.php?id=<?php echo urlencode($book['id']); ?>" class="block text-lg font-medium text-blue-900 hover:underline">
                            <?php echo htmlspecialchars($book['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </div>
</body>

</html>