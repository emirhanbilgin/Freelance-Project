<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo Section -->
            <div class="text-center">
                <div class="mx-auto w-20 h-20 mb-4">
                    <img src="{{ asset('logo.png') }}" alt="Hakan Gıda" class="w-full h-full object-contain">
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Hakan Gıda
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Sipariş Yönetim Sistemi
                </p>
            </div>

            <!-- Login Form -->
            <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow-xl rounded-2xl">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white text-center mb-6">
                    Giriş Yap
                </h3>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
                        <x-text-input id="email" class="block mt-2 w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email adresinizi girin" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Şifre')" class="text-gray-700 dark:text-gray-300" />
                        <x-text-input id="password" class="block mt-2 w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" 
                                        placeholder="Şifrenizi girin" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 shadow-sm focus:ring-primary-500 dark:bg-gray-700" name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Beni hatırla') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300" href="{{ route('password.request') }}">
                                {{ __('Şifremi unuttum?') }}
                            </a>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('Giriş Yap') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    © 2025 Hakan Gıda. Tüm hakları saklıdır.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
