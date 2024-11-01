<?php
// เชื่อมต่อกับฐานข้อมูล
$connect = mysqli_connect('readnet-db.cwbexon7radv.us-east-1.rds.amazonaws.com', 'admin', 'Password123!', 'php_docker');

// ตรวจสอบการเชื่อมต่อ
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// รับ ID ของหนังสือจากพารามิเตอร์ URL
$book_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($book_id) {
    // คิวรีเพื่อดึงข้อมูลหนังสือ
    $query = "SELECT * FROM Books WHERE book_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 's', $book_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($result);
    
    // ตรวจสอบว่าพบหนังสือหรือไม่
    if (!$book) {
        echo "Book not found.";
        exit; // ออกจากการทำงานหากไม่พบหนังสือ
    }
} else {
    echo "No book ID provided.";
    exit; // ออกจากการทำงานหากไม่มี ID
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title><?php echo htmlspecialchars($book['title'], ENT_QUOTES); ?></title>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="max-w-2xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($book['title'], ENT_QUOTES); ?></h1>
        <p class="mt-4 text-lg">Author: <?php echo htmlspecialchars($book['author'], ENT_QUOTES); ?></p>
        <p class="mt-2">Category: <?php echo htmlspecialchars($book['category_id'], ENT_QUOTES); ?></p>
        <img src="<?php echo htmlspecialchars($book['image_url'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($book['title'], ENT_QUOTES); ?>" class="mt-4 rounded-md">
        <p class="mt-4"><?php echo nl2br(htmlspecialchars($book['description'], ENT_QUOTES)); ?></p>
    </div>
</body>

</html>
