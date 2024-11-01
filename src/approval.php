<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ReadNet - Pending Books</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white font-sans">

<?php include 'navbar_admin.php'; ?>

  <!-- Content -->
  <div class="container mx-auto py-10 px-4">
    
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

    // Fetch books with status 'pending'
    $query_pending = "SELECT title, author, image_url, created_at FROM Books WHERE status = 'pending'";
    $result_pending = mysqli_query($connect, $query_pending);

    // Fetch books with status 'approved'
    $query_approved = "SELECT title, author, image_url, created_at FROM Books WHERE status = 'approved'";
    $result_approved = mysqli_query($connect, $query_approved);

    // Display Pending Books
    echo "<h2 class=\"text-xl font-bold mb-6\">Pending Books</h2>";

    if (mysqli_num_rows($result_pending) > 0) {
        while ($row = mysqli_fetch_assoc($result_pending)) {
            $title = htmlspecialchars($row['title']);
            $author = htmlspecialchars($row['author']);
            $image_url = htmlspecialchars($row['image_url']);
            $created_at = date('F j, Y, g:i a', strtotime($row['created_at'])); // Format the date
            
            echo "
            <div onclick=\"viewBookDetails('$title')\" class=\"bg-blue-700 text-white p-4 mb-4 rounded-lg flex items-center justify-between cursor-pointer\">
              <div class=\"flex items-center\">
                <img src=\"$image_url\" alt=\"Book Thumbnail\" class=\"rounded-md w-16 h-16 mr-4\">
                <div>
                  <h3 class=\"text-lg font-semibold\">$title</h3>
                  <p class=\"text-sm text-gray-300\">$author</p>
                  <p class=\"text-xs text-gray-400\">$created_at</p>
                </div>
              </div>
              <div class=\"flex space-x-2\">
                <button onclick=\"approveBook(event, '$title')\" class=\"bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded\">Approve</button>
                <button onclick=\"rejectBook(event, '$title')\" class=\"bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded\">Reject</button>
              </div>
            </div>";
        }
    } else {
        echo "<p class=\"text-gray-400\">No pending books available.</p>";
    }

    // Display Approved Books
    echo "<h2 class=\"text-xl font-bold mb-6\">Approved Books</h2>";

    if (mysqli_num_rows($result_approved) > 0) {
        while ($row = mysqli_fetch_assoc($result_approved)) {
            $title = htmlspecialchars($row['title']);
            $author = htmlspecialchars($row['author']);
            $image_url = htmlspecialchars($row['image_url']);
            $created_at = date('F j, Y, g:i a', strtotime($row['created_at'])); // Format the date
            
            echo "
            <div class=\"bg-green-700 text-white p-4 mb-4 rounded-lg flex items-center justify-between\">
              <div class=\"flex items-center\">
                <img src=\"$image_url\" alt=\"Book Thumbnail\" class=\"rounded-md w-16 h-16 mr-4\">
                <div>
                  <h3 class=\"text-lg font-semibold\">$title</h3>
                  <p class=\"text-sm text-gray-300\">$author</p>
                  <p class=\"text-xs text-gray-400\">$created_at</p>
                </div>
              </div>
            </div>";
        }
    } else {
        echo "<p class=\"text-gray-400\">No approved books available.</p>";
    }

    // Close the connection
    mysqli_close($connect);
    ?>
    
  </div>

  <script>
  function approveBook(event, title) {
      event.stopPropagation(); // Prevent click event from bubbling up to the parent div
      if (confirm('Are you sure you want to approve this book?')) {
          fetch('update_book_status.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify({ title: title, status: 'approved' })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Book approved successfully!');
                  location.reload(); // Reload the page to see the updated list
              } else {
                  alert('Failed to approve book: ' + data.message);
              }
          })
          .catch(error => {
              console.error('Error:', error);
          });
      }
  }

  function rejectBook(event, title) {
      event.stopPropagation(); // Prevent click event from bubbling up to the parent div
      if (confirm('Are you sure you want to reject this book?')) {
          fetch('update_book_status.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify({ title: title, status: 'rejected' })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Book rejected successfully!');
                  location.reload(); // Reload the page to see the updated list
              } else {
                  alert('Failed to reject book: ' + data.message);
              }
          })
          .catch(error => {
              console.error('Error:', error);
          });
      }
  }
</script>

</body>
</html>
