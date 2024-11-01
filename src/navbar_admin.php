
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
                            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                            <a href="index.php" class="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white" aria-current="page">Aprrove</a>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <button type="button" class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">View notifications</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                    </button>

                    <!-- Profile dropdown -->
                    <nav class="bg-gray-800">
                        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                            <div class="relative flex h-16 items-center justify-between">
                                <!-- ส่วนของเมนูอื่นๆ -->

                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                                    <!-- Profile dropdown -->
                                    <div class="relative ml-3">
                                        <div>
                                            <button id="profileButton" type="button" class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-haspopup="true">
                                                <span class="sr-only">Open user menu</span>
                                                <img class="h-8 w-8 rounded-full" src="https://i.pinimg.com/originals/f1/0f/f7/f10ff70a7155e5ab666bcdd1b45b726d.jpg" alt="">
                                            </button>
                                        </div>

                                        <div id="dropdownMenu" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 hidden" role="menu" aria-orientation="vertical" tabindex="-1">
                                            <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700" role="menuitem">Sign out</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                    
                    <script>
                        // จัดการการคลิกเพื่อแสดง/ซ่อน dropdown
                        document.getElementById('profileButton').addEventListener('click', function() {
                            var dropdownMenu = document.getElementById('dropdownMenu');
                            dropdownMenu.classList.toggle('hidden'); // แสดง/ซ่อน dropdown
                        });

                        // ซ่อน dropdown เมื่อคลิกนอก dropdown
                        window.onclick = function(event) {
                            var dropdownMenu = document.getElementById('dropdownMenu');
                            if (!event.target.closest('#profileButton') && !event.target.closest('#dropdownMenu')) {
                                if (!dropdownMenu.classList.contains('hidden')) {
                                    dropdownMenu.classList.add('hidden');
                                }
                            }
                        };
                    </script>
                </div>
            </div>
        </div>
    </nav>
    
</body>

</html>