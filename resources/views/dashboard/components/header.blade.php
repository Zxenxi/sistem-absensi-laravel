<header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
    <div class="container flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
        <!-- Tombol menu untuk mobile -->
        <button @click="toggleSideMenu" class="p-1 mr-5 -ml-1 rounded-md focus:outline-none focus:shadow-outline-purple">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zM3 10h14a1 1 0 010 2H3a1 1 0 010-2zM3 15h14a1 1 0 010 2H3a1 1 0 010-2z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
        <!-- Menu pengguna -->
        <ul class="flex items-center flex-shrink-0 space-x-6">
            <li class="relative">
                <!-- Dropdown profil -->
                <button class="rounded-full focus:shadow-outline-purple focus:outline-none">
                    <img class="w-8 h-8 rounded-full" src="{{ asset('path/to/user/image.jpg') }}" alt="User Image">
                </button>
            </li>
        </ul>
    </div>
</header>
