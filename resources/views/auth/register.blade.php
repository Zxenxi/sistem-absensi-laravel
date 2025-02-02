<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="{ role: '{{ old('role', '') }}' }" lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create account - Windmill Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.output.css') }}" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="{{ asset('assets/js/init-alpine.js') }}"></script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <div class="flex items-center min-h-screen p-6">
        <div
            class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white/30 backdrop-blur-lg rounded-lg shadow-xl dark:bg-gray-800">
            <div class="flex flex-col md:flex-row">
                <!-- Kolom Gambar -->
                <div class="h-32 md:h-auto md:w-1/2">
                    <img class="object-cover w-full h-full dark:hidden"
                        src="{{ asset('assets/img/create-account-office.jpeg') }}" alt="Office" />
                    <img class="hidden object-cover w-full h-full dark:block"
                        src="{{ asset('assets/img/create-account-office-dark.jpeg') }}" alt="Office" />
                </div>
                <!-- Kolom Form -->
                <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
                    <div class="w-full">
                        <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
                            Create account
                        </h1>
                        <form method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf
                            <!-- Name -->
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input id="name" name="name" type="text" required autofocus
                                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 form-input"
                                    placeholder="Your Name" />
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div class="mt-4">
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input id="email" name="email" type="email" required
                                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 form-input"
                                    placeholder="example@example.com" />
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Password -->
                            <div class="mt-4">
                                <label for="password"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                <input id="password" name="password" type="password" required
                                    autocomplete="new-password"
                                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 form-input"
                                    placeholder="***************" />
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Confirm Password -->
                            <div class="mt-4">
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm
                                    Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 form-input"
                                    placeholder="***************" />
                                @error('password_confirmation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Role Selection -->
                            <div class="mt-4">
                                <label for="role"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Daftar
                                    Sebagai</label>
                                <select id="role" name="role" x-model="role"
                                    class="block w-full mt-1 border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Role</option>
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                </select>
                                @error('role')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Field NISN (hanya muncul jika role = siswa) -->
                            <div class="mt-4" x-show="role === 'siswa'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0">
                                <label for="nisn"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">NISN</label>
                                <input id="nisn" name="nisn" type="text"
                                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 form-input"
                                    placeholder="Masukkan NISN" />
                                @error('nisn')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Tombol Register -->
                            <div class="flex items-center justify-end mt-6">
                                <a class="underline text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900"
                                    href="{{ route('login') }}">
                                    Already registered?
                                </a>
                                <button type="submit"
                                    class="ml-4 w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                    Register
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
