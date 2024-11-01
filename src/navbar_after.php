<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex flex-shrink-0 items-center">
                        <img class="h-8 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4">
                            <a href="index.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Home</a>
                            <a href="featured.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Featured</a>
                            <a href="manga_list.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Manga list</a>
                            <a href="about_us.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">About Us</a>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <!-- Remove absolute span and directly wrap link -->
                    <a href="add_book.php" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Create Book</a>

                    <!-- Profile dropdown -->
                    <div class="relative ml-3">
                        <button id="profileButton" type="button" class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full" src="https://i.pinimg.com/originals/f1/0f/f7/f10ff70a7155e5ab666bcdd1b45b726d.jpg" alt="">
                        </button>

                        <div id="dropdownMenu" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 hidden" role="menu" aria-orientation="vertical" tabindex="-1">
                            <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700" role="menuitem">Your Profile</a>
                            <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700" role="menuitem">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        // Toggle dropdown on profile button click
        document.getElementById('profileButton').addEventListener('click', function() {
            var dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('hidden');
        });

        // Hide dropdown when clicking outside
        window.onclick = function(event) {
            var dropdownMenu = document.getElementById('dropdownMenu');
            if (!event.target.closest('#profileButton') && !event.target.closest('#dropdownMenu')) {
                dropdownMenu.classList.add('hidden');
            }
        };
    </script>
</body>

</html>
