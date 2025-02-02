<x-guest-layout>
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
        <div
            class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white/30 backdrop-blur-lg rounded-lg shadow-xl dark:bg-gray-800">
            <div class="flex flex-col overflow-y-auto md:flex-row">
                <!-- Kolom Gambar -->
                <div class="h-32 md:h-auto md:w-1/2">
                    <img aria-hidden="true" class="object-cover w-full h-full dark:hidden"
                        src="{{ asset('assets/img/create-account-office.jpeg') }}" alt="Office" />
                    <img aria-hidden="true" class="hidden object-cover w-full h-full dark:block"
                        src="{{ asset('assets/img/create-account-office-dark.jpeg') }}" alt="Office" />
                </div>
                <!-- Kolom Form -->
                <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2" x-data="{ role: '{{ old('role', '') }}' }">
                    <div class="w-full">
                        <h1 class="mb-6 text-2xl font-bold text-gray-800 dark:text-gray-200">Create account</h1>
                        <form method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <!-- Email -->
                            <div class="mt-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <!-- Password -->
                            <div class="mt-4">
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                    required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <!-- Confirm Password -->
                            <div class="mt-4">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                    name="password_confirmation" required />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                            <!-- Role Selection -->
                            <div class="mt-4">
                                <x-input-label for="role" :value="__('Daftar Sebagai')" />
                                <select id="role" name="role" x-model="role"
                                    class="block w-full mt-1 border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Pilih Role</option>
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>
                            <!-- Field NISN (hanya muncul jika role = siswa) -->
                            <div class="mt-4" x-show="role === 'siswa'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0">
                                <x-input-label for="nisn" :value="__('NISN')" />
                                <x-text-input id="nisn" class="block mt-1 w-full" type="text" name="nisn"
                                    placeholder="Masukkan NISN" />
                                <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
                            </div>
                            <!-- Tombol Register -->
                            <div class="flex items-center justify-end mt-6">
                                <a class="underline text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900"
                                    href="{{ route('login') }}">
                                    Already registered?
                                </a>
                                <x-primary-button class="ml-4">
                                    {{ __('Register') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
