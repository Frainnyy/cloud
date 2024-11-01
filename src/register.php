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
            <form class="space-y-6" action="#" method="POST">
                <div>
                    <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                    <div class="mt-2">
                        <input id="username" name="username" type="text" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="confirm-password" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                    <div class="mt-2">
                        <input id="confirm-password" name="confirm-password" type="password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Register</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Already a member?
                <a href="#" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Sign in</a>
            </p>
        </div>
    </div>
    <?php
    // ฟังก์ชันเชื่อมต่อฐานข้อมูล
            $connection = mysqli_connect('db', 'php_docker', 'passwordd', 'php_docker');

            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user = $_POST['username'];
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $confirmPass = $_POST['confirm-password'];
        
            // Check if passwords match
            if ($pass !== $confirmPass) {
                echo "<script>
                alert('Passwords do not match. Please rewrite the password');
                </script>";
                die();
            }
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
            $statement = $connection->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
            $statement->bind_param("sss", $user, $email, $hashedPass);

            if ($statement->execute()) {
                echo "<script>
                alert('Registration successful! You will be redirected to the login page.');
                window.location.href = 'login.php';
                </script>";                         
            } else {
                echo "<script>
                alert('\"Error: \" . $statement->error;);
                </script>";
            }

            $statement->close();
            $connection->close();
        }
    ?>
</body>


</html>