<?php
session_start(); // Ensure session is started at the top

if (!isset($_SESSION['user_id'])) {
    echo "User not found or not logged in.";
    exit(); 
}

$user_id = $_SESSION['user_id']; // Store the user_id from session

echo "<div class='bg-green-100 p-4 text-green-800'>Logged in as User ID: " . $_SESSION['user_id'] . "</div>";

function connectDatabase($host, $username, $password, $database)
{
    $connection = mysqli_connect($host, $username, $password, $database);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $connection;
}

function addBook($connect, $user_id, $title, $author, $img_url, $category, $content)
{
    // Clean the received data
    $title = mysqli_real_escape_string($connect, $title);
    $author = mysqli_real_escape_string($connect, $author);
    $img_url = mysqli_real_escape_string($connect, $img_url);
    $category = mysqli_real_escape_string($connect, $category);
    $content = mysqli_real_escape_string($connect, $content);

    $query = "INSERT INTO Books (created_by, title, author, image_url, category_id, description) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'isssis', $user_id, $title, $author, $img_url, $category, $content);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        return true; // Return true if successful
    } else {
        return false; // Return false if there was an error
    }
}

define('DB_HOST', 'readnet-db.cwbexon7radv.us-east-1.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'Password123!');
define('DB_NAME', 'php_docker');

$connect = connectDatabase(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $img_url = $_POST['img_url'];
    $category_id = $_POST['category']; 
    $content = $_POST['content'];

    // Add the novel to the database
    if (addBook($connect, $user_id, $title, $author, $img_url, $category_id, $content)) {
        $successMessage = "Book added successfully!";
    } else {
        $successMessage = "Failed to add the book. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Books - ReadNet</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include 'navbar.php'; ?>
    <main class="flex items-center justify-center min-h-screen py-8">
        <div class="container mx-auto flex justify-center p-10 bg-white rounded-lg shadow-md">
            <div class="md:w-2/3 w-full">
                <?php if ($successMessage): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline"><?php echo $successMessage; ?></span>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">                                        
                    <label for="title" class="mt-4 font-semibold">Title</label>
                    <input type="text" id="title" name="title" placeholder="Enter novel name" class="mt-2 p-2 border rounded w-full" required>

                    <label for="author" class="mt-4 font-semibold">Author</label>
                    <input type="text" id="author" name="author" placeholder="Enter author name" class="mt-2 p-2 border rounded w-full" required>

                    <label for="img_url" class="mt-4 font-semibold">Image URL</label>
                    <input type="text" id="img_url" name="img_url" placeholder="Enter image URL" class="mt-2 p-2 border rounded w-full" required>

                    <label class="mt-4 font-semibold">Categories</label>
                    <div class="flex flex-wrap mt-2 gap-2">
                        <!-- Hidden input to hold the selected category -->
                        <input type="hidden" id="category" name="category" value="">
                        <button type="button" onclick="selectCategory(event)" class="category-btn px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Romance</button>
                        <button type="button" onclick="selectCategory(event)" class="category-btn px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Yaoi</button>
                        <button type="button" onclick="selectCategory(event)" class="category-btn px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Horror</button>
                        <button type="button" onclick="selectCategory(event)" class="category-btn px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Fantasy</button>
                        <button type="button" onclick="selectCategory(event)" class="category-btn px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Mystery</button>
                        <button type="button" onclick="selectCategory(event)" class="category-btn px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Children's</button>
                    </div>

                    <label for="content" class="mt-4 font-semibold">Content</label>
                    <textarea id="content" name="content" rows="10" placeholder="Enter novel content here" class="mt-2 p-2 border rounded w-full" required></textarea>

                    <button type="submit" class="w-full mt-4 py-2 px-4 bg-green-500 text-white font-semibold rounded hover:bg-green-600">
                        ADD STORY!
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        const categories = {
            "Romance": 1,
            "Yaoi": 2,
            "Horror": 3,
            "Fantasy": 4,
            "Mystery": 5,
            "Children's": 6
        };

        function selectCategory(event) {
            // Deselect any previously selected category
            const categoryButtons = document.querySelectorAll(".category-btn");
            categoryButtons.forEach(button => {
                button.classList.remove("bg-blue-500", "text-white");
                button.classList.add("bg-gray-200", "text-gray-700");
            });
            // Select the clicked category
            event.target.classList.remove("bg-gray-200", "text-gray-700");
            event.target.classList.add("bg-blue-500", "text-white");
            
            const selectedCategory = event.target.innerText;
            document.getElementById("category").value = categories[selectedCategory]; // Set selected category ID
        }
    </script>
</body>
</html>
