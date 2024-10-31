<?php
// การตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = 'db';
$username = 'php_docker';
$password = 'password';
$dbname = 'php_docker';

// สร้างการเชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // ตรวจสอบว่ารหัสผ่านตรงกัน
    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // เตรียมคำสั่ง SQL
        $stmt = $conn->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Registration successful!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Passwords do not match!');</script>";
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Register</title>
</head>
<body>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Create an Account</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="register.php" method="POST">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                    <input id="name" name="name" type="text" required class="block w-full rounded-md border-gray-300 py-2 px-3 text-gray-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                    <input id="username" name="username" type="text" required class="block w-full rounded-md border-gray-300 py-2 px-3 text-gray-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                    <input id="email" name="email" type="email" required class="block w-full rounded-md border-gray-300 py-2 px-3 text-gray-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <input id="password" name="password" type="password" required class="block w-full rounded-md border-gray-300 py-2 px-3 text-gray-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="confirm-password" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                    <input id="confirm-password" name="confirm-password" type="password" required class="block w-full rounded-md border-gray-300 py-2 px-3 text-gray-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md font-semibold shadow hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-500">Register</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>