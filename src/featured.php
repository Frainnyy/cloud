<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Featured</title>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php

    // ฟังก์ชันเชื่อมต่อฐานข้อมูล
    function connectDatabase($host, $username, $password, $database)
    {
        $connection = mysqli_connect($host, $username, $password, $database);

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        return $connection;
    }

    // ฟังก์ชันเพิ่มหนังสือ
    function addBook($connect, $title, $author, $isbn, $category_id, $image_url, $description)
    {
        $query = "INSERT INTO books (title, author, isbn, category_id, image_url, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);

        // ผูกค่าที่จะเพิ่ม
        mysqli_stmt_bind_param($stmt, 'ssssss', $title, $author, $isbn, $category_id, $image_url, $description);

        // ประมวลผลคำสั่ง
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // ค่าคงที่สำหรับฐานข้อมูล RDS
    define('DB_HOST', 'YOUR_RDS_ENDPOINT'); // เปลี่ยนเป็น RDS endpoint ของคุณ
    define('DB_USERNAME', 'admin'); // ชื่อผู้ใช้ RDS
    define('DB_PASSWORD', 'Password123!'); // รหัสผ่านของ RDS
    define('DB_NAME', 'php_docker'); // ชื่อฐานข้อมูลที่คุณต้องการเข้าถึง

    // เชื่อมต่อฐานข้อมูล
    $connect = connectDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // ชื่อของตาราง
    $table_name_books = "books";
    $table_name_categories = "categories";

    // JOIN ตาราง books กับ categories
    $query = "
    SELECT b.*, c.name AS category_name
    FROM $table_name_books b
    LEFT JOIN $table_name_categories c ON b.category_id = c.id
";

    $response = mysqli_query($connect, $query);

    ?>

    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Horror</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                <?php
                // JOIN ตาราง books กับ categories และเลือกเฉพาะหนังสือที่เป็นหมวดหมู่ 'horror'
                $query = "
                SELECT b.*, c.name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.id
                WHERE c.name = 'Horror'";

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
                                <a href='book_details.php?id=" . $book['id'] . "'>
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
                SELECT b.*, c.name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.id
                WHERE c.name = 'Romance'";

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
                                <a href='book_details.php?id=" . $book['id'] . "'>
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
                SELECT b.*, c.name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.id
                WHERE c.name = 'Yaoi'";

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
                                <a href='book_details.php?id=" . $book['id'] . "'>
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
                SELECT b.*, c.name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.id
                WHERE c.name = 'Fantasy'";

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
                                <a href='book_details.php?id=" . $book['id'] . "'>
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
                SELECT b.*, c.name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.id
                WHERE c.name = 'Mystery'";

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
                                <a href='book_details.php?id=" . $book['id'] . "'>
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
                SELECT b.*, c.name AS category_name
                FROM $table_name_books b
                LEFT JOIN $table_name_categories c ON b.category_id = c.id
                WHERE c.name = 'Children\'s'";

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
                                <a href='book_details.php?id=" . $book['id'] . "'>
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