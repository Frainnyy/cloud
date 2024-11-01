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

// ชื่อของตาราง
$table_name_books = "Books";
$table_name_categories = "Categories";

// JOIN ตาราง books กับ categories
$query = "
SELECT b.*, c.category_name AS category_name
FROM $table_name_books b
LEFT JOIN $table_name_categories c ON b.category_id = c.category_id
";

$response = mysqli_query($connect, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Featured</title>
</head>

<body>

    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Horror</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <?php
                // JOIN ตาราง books กับ categories และเลือกเฉพาะหนังสือที่เป็นหมวดหมู่ 'horror'
                $query = "
                SELECT b.*, c.category_name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.category_id
                WHERE c.category_name = 'Horror' AND b.status = 'approved'";
                

                $response = mysqli_query($connect, $query);

                // Loop ผ่านข้อมูลหนังสือ
                while ($book = mysqli_fetch_assoc($response)) {
                    $title = $book['title'];                 // ชื่อหนังสือ
                    $categoryName = $book['category_name'];  // ชื่อหมวดหมู่
                    $imageUrl = $book['image_url'];          // URL ของภาพ

                    // แสดงข้อมูลหนังสือในรูปแบบที่ต้องการ
                    echo "
                <div class='group relative'>
                    <div class='aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80'>
                        <img src='$imageUrl' alt='$title' class='h-full w-full object-cover object-center lg:h-full lg:w-full'>
                    </div>
                    <div class='mt-4 flex justify-between'>
                        <div>
                            <h3 class='text-sm text-gray-700'>
                                <a href='book_details.php?id=" . $book['book_id'] . "'>
                                    <span aria-hidden='true' class='absolute inset-0'></span>
                                    $title
                                </a>
                            </h3>
                            <p class='mt-1 text-sm text-gray-500'>หมวดหมู่: $categoryName</p>
                        </div>
                    </div>
                </div>
                ";
                }
                ?>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Romance</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <?php
                // JOIN ตาราง books กับ categories
                $query = "
                SELECT b.*, c.category_name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.category_id
                WHERE c.category_name = 'Romance' AND b.status = 'approved'";

                $response = mysqli_query($connect, $query);

                // Loop ผ่านข้อมูลหนังสือ
                while ($book = mysqli_fetch_assoc($response)) {
                    $title = $book['title'];                 // ชื่อหนังสือ
                    $categoryName = $book['category_name'];  // ชื่อหมวดหมู่
                    $imageUrl = $book['image_url'];          // URL ของภาพ

                    // แสดงข้อมูลหนังสือในรูปแบบที่ต้องการ
                    echo "
                <div class='group relative'>
                    <div class='aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80'>
                        <img src='$imageUrl' alt='$title' class='h-full w-full object-cover object-center lg:h-full lg:w-full'>
                    </div>
                    <div class='mt-4 flex justify-between'>
                        <div>
                            <h3 class='text-sm text-gray-700'>
                                <a href='book_details.php?id=" . $book['book_id'] . "'>
                                    <span aria-hidden='true' class='absolute inset-0'></span>
                                    $title
                                </a>
                            </h3>
                            <p class='mt-1 text-sm text-gray-500'>หมวดหมู่: $categoryName</p>
                        </div>
                    </div>
                </div>
                ";
                }
                ?>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Yaoi</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <?php
                // JOIN ตาราง books กับ categories
                $query = "
                SELECT b.*, c.category_name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.category_id
                WHERE c.category_name = 'Yaoi'";

                $response = mysqli_query($connect, $query);

                // Loop ผ่านข้อมูลหนังสือ
                while ($book = mysqli_fetch_assoc($response)) {
                    $title = $book['title'];                 // ชื่อหนังสือ
                    $categoryName = $book['category_name'];  // ชื่อหมวดหมู่
                    $imageUrl = $book['image_url'];          // URL ของภาพ

                    // แสดงข้อมูลหนังสือในรูปแบบที่ต้องการ
                    echo "
                <div class='group relative'>
                    <div class='aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80'>
                        <img src='$imageUrl' alt='$title' class='h-full w-full object-cover object-center lg:h-full lg:w-full'>
                    </div>
                    <div class='mt-4 flex justify-between'> 
                        <div>
                            <h3 class='text-sm text-gray-700'>
                                <a href='book_details.php?id=" . $book['book_id'] . "'>
                                    <span aria-hidden='true' class='absolute inset-0'></span>
                                    $title
                                </a>
                            </h3>
                            <p class='mt-1 text-sm text-gray-500'>หมวดหมู่: $categoryName</p>
                        </div>
                    </div>
                </div>
                ";
                }
                ?>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Fantasy</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <?php
                // JOIN ตาราง books กับ categories
                $query = "
                SELECT b.*, c.category_name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.category_id
                WHERE c.category_name = 'Fantasy' AND b.status = 'approved'";

                $response = mysqli_query($connect, $query);

                // Loop ผ่านข้อมูลหนังสือ
                while ($book = mysqli_fetch_assoc($response)) {
                    $title = $book['title'];                 // ชื่อหนังสือ
                    $categoryName = $book['category_name'];  // ชื่อหมวดหมู่
                    $imageUrl = $book['image_url'];          // URL ของภาพ

                    // แสดงข้อมูลหนังสือในรูปแบบที่ต้องการ
                    echo "
                <div class='group relative'>
                    <div class='aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80'>
                        <img src='$imageUrl' alt='$title' class='h-full w-full object-cover object-center lg:h-full lg:w-full'>
                    </div>
                    <div class='mt-4 flex justify-between'>
                        <div>
                            <h3 class='text-sm text-gray-700'>
                                <a href='book_details.php?id=" . $book['book_id'] . "'>
                                    <span aria-hidden='true' class='absolute inset-0'></span>
                                    $title
                                </a>
                            </h3>
                            <p class='mt-1 text-sm text-gray-500'>หมวดหมู่: $categoryName</p>
                        </div>
                    </div>
                </div>
                ";
                }
                ?>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Mystery</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <?php
                // JOIN ตาราง books กับ categories
                $query = "
                SELECT b.*, c.category_name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.category_id
                WHERE c.category_name = 'Mystery' AND b.status = 'approved'";

                $response = mysqli_query($connect, $query);

                // Loop ผ่านข้อมูลหนังสือ
                while ($book = mysqli_fetch_assoc($response)) {
                    $title = $book['title'];                 // ชื่อหนังสือ
                    $categoryName = $book['category_name'];  // ชื่อหมวดหมู่
                    $imageUrl = $book['image_url'];          // URL ของภาพ

                    // แสดงข้อมูลหนังสือในรูปแบบที่ต้องการ
                    echo "
                <div class='group relative'>
                    <div class='aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80'>
                        <img src='$imageUrl' alt='$title' class='h-full w-full object-cover object-center lg:h-full lg:w-full'>
                    </div>
                    <div class='mt-4 flex justify-between'>
                        <div>
                            <h3 class='text-sm text-gray-700'>
                                <a href='book_details.php?id=" . $book['book_id'] . "'>
                                    <span aria-hidden='true' class='absolute inset-0'></span>
                                    $title
                                </a>
                            </h3>
                            <p class='mt-1 text-sm text-gray-500'>หมวดหมู่: $categoryName</p>
                        </div>
                    </div>
                </div>
                ";
                }
                ?>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Children's</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <?php
                // JOIN ตาราง books กับ categories
                $query = "
                SELECT b.*, c.category_name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.category_id
                WHERE c.category_name = 'Children\'s' AND b.status = 'approved'";

                $response = mysqli_query($connect, $query);

                // Loop ผ่านข้อมูลหนังสือ
                while ($book = mysqli_fetch_assoc($response)) {
                    $title = $book['title'];                 // ชื่อหนังสือ
                    $categoryName = $book['category_name'];  // ชื่อหมวดหมู่
                    $imageUrl = $book['image_url'];          // URL ของภาพ

                    // แสดงข้อมูลหนังสือในรูปแบบที่ต้องการ
                    echo "
                <div class='group relative'>
                    <div class='aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80'>
                        <img src='$imageUrl' alt='$title' class='h-full w-full object-cover object-center lg:h-full lg:w-full'>
                    </div>
                    <div class='mt-4 flex justify-between'>
                        <div>
                            <h3 class='text-sm text-gray-700'>
                                <a href='book_details.php?id=" . $book['book_id'] . "'>
                                    <span aria-hidden='true' class='absolute inset-0'></span>
                                    $title
                                </a>
                            </h3>
                            <p class='mt-1 text-sm text-gray-500'>หมวดหมู่: $categoryName</p>
                        </div>
                    </div>
                </div>
                ";
                }
                ?>
            </div>
        </div>
    </div>


</body>

</html>
