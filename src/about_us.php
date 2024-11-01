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

?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>เกี่ยวกับเรา</title>
</head>
<body class="bg-gray-50 font-sans text-gray-800">


<div class="max-w-2xl mx-auto mt-16 p-8 bg-white rounded-lg shadow-lg text-center">
    <h1 class="text-4xl font-semibold mb-6 text-gray-800">เกี่ยวกับเรา</h1>
    <p class="mb-4 text-base leading-relaxed text-gray-600">ยินดีต้อนรับสู่เว็บไซต์อ่านนิยายของเรา! เรามีความตั้งใจในการให้บริการข้อมูลที่ดีที่สุดสำหรับผู้ใช้งานทุกคน เว็บไซต์นี้สร้างขึ้นเพื่อเป็นแหล่งข้อมูลที่น่าเชื่อถือและเข้าถึงง่าย</p>
    
    <p class="mb-6 bg-gray-100 p-4 rounded-md text-gray-600">ทีมงานของเรามุ่งมั่นพัฒนาและนำนวัตกรรมใหม่ๆ เพื่อให้มั่นใจว่าผู้ใช้งานจะได้รับประสบการณ์ที่ดีจากการใช้งานเว็บไซต์ของเรา</p>

    <div class="mt-10 border-t pt-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">จัดทำโดย</h2>
        <p class="text-gray-500">นางสาวณัฐนันท์ งามสมุทร 65070077<br>
           นายธนกฤต สิงห์สังข์ 65070090<br>
           นางสาวปณาลี จุกสีดา 65070127<br>
           นายพรเสก ชื่นมี 65070147<br>
           นางสาวพิราภรณ์ ประเสริฐ 65070157<br>
           นายภควัฒน์ พันธุ์ภักดีวงษ์ 65070165</p>
        
        <h2 class="text-xl font-semibold text-gray-700 mt-8 mb-4">เสนอ</h2>
        <p class="text-gray-500">ผศ.ดร. พัฒนพงษ์ ฉันทมิตรโอภาส<br>
           ผศ.ดร. ลภัส ประดิษฐ์ทัศนีย์</p>
        
        <h2 class="text-xl font-semibold text-gray-700 mt-8 mb-4">โครงการนี้เป็นส่วนหนึ่งของ</h2>
        <p class="text-gray-500">วิชาเทคโนโลยีกลุ่มเมฆ<br>
           แขนงวิชา โครงสร้างพื้นฐานเทคโนโลยีสารสนเทศ<br>
           คณะเทคโนโลยีสารสนเทศ<br>
           สถาบันเทคโนโลยีพระจอมเกล้าเจ้าคุณทหารลาดกระบัง<br>
           ภาคเรียนที่ 1 ปีการศึกษา 2567</p>
    </div>
</div>

<footer class="text-center mt-16 mb-8 text-gray-400">
    &copy; <?php echo date("Y"); ?> เว็บไซต์อ่านนิยายของเรา - ทุกสิทธิ์สงวน
</footer>

</body>
</html>
