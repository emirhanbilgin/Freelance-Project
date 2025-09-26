<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#3b82f6">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Hakan Gıda') }}">

        <title>{{ config('app.name', 'Hakan Gıda Sipariş') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Mobile touch improvements -->
        <style>
            /* Prevent zoom on input focus on iOS */
            input[type="text"], input[type="number"], input[type="email"], input[type="password"], textarea, select {
                font-size: 16px !important;
            }
            
            /* Better touch targets */
            button, a, input[type="button"], input[type="submit"] {
                min-height: 44px;
                min-width: 44px;
            }
            
            /* Smooth scrolling */
            html {
                scroll-behavior: smooth;
            }
            
            /* Better mobile spacing */
            @media (max-width: 640px) {
                .mobile-p-4 {
                    padding: 1rem;
                }
                .mobile-mb-4 {
                    margin-bottom: 1rem;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Navigation -->
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Mobile bottom navigation for quick access -->
        <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 md:hidden">
            <div class="flex justify-around py-2">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-500' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    <span class="text-xs">Ana Sayfa</span>
                </a>
                <a href="{{ route('customers.index') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('customers.*') ? 'text-primary-600' : 'text-gray-500' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="text-xs">Müşteriler</span>
                </a>
                <a href="{{ route('receipts.archived') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('receipts.archived') ? 'text-primary-600' : 'text-gray-500' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <span class="text-xs">Arşiv</span>
                </a>
                <a href="{{ route('receipts.credit') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('receipts.credit') ? 'text-primary-600' : 'text-gray-500' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    <span class="text-xs">Veresiye</span>
                </a>
            </div>
        </div>
    </body>
</html>
