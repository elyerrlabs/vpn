<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('VPN Premium - WireGuard + Proxy + SOCKS5') }}</title>
    <meta name="description"
        content="{{ __('Secure your internet connection with our premium VPN service featuring WireGuard, HTTP/HTTPS Proxy, and SOCKS5 protocols. Fast, secure, and anonymous browsing.') }}">
    <meta name="keywords"
        content="{{ __('VPN, WireGuard, Proxy, SOCKS5, secure connection, privacy, encryption, anonymous browsing') }}">
    <meta name="author" content="ElyerrLabs">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ __('VPN Premium - Secure Your Digital Life') }}">
    <meta property="og:description"
        content="{{ __('Fast, secure, and anonymous VPN service with WireGuard, Proxy, and SOCKS5 support.') }}">
    @if (file_exists(public_path('third-party/vpn/og-image.png')))
        <meta property="og:image" content="{{ asset('third-party/vpn/og-image.png') }}">
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('VPN Premium - Secure Your Digital Life') }}">
    <meta name="twitter:description"
        content="{{ __('Fast, secure, and anonymous VPN service with WireGuard, Proxy, and SOCKS5 support.') }}">

    @if (file_exists(public_path('third-party/vpn/favicon.png')))
        <link rel="icon" href="{{ asset('third-party/vpn/favicon.png') }}" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('third-party/vpn/favicon.png') }}">
    @endif

    <!-- Tailwind CSS v4 via CDN -->
    <script nonce="{{ $nonce }}" src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config with Dark Mode -->
    <script nonce="{{ $nonce }}">
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554'
                        },
                        accent: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7e22ce',
                            800: '#6b21a8',
                            900: '#581c87',
                            950: '#3b0764'
                        }
                    },
                    backgroundColor: {
                        'dark': {
                            'primary': '#0f172a',
                            'secondary': '#1e293b',
                            'card': '#334155',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome 6 (Free) -->
    <link nonce="{{ $nonce }}" rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link nonce="{{ $nonce }}" href="{{ asset('third-party/vpn/css/app.css') }}" rel="stylesheet">

    <!-- Additional CSS for animations and modern features -->
    <style nonce="{{ $nonce }}">
        /* Custom animations */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes pulse-glow {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.5);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
            }
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        .animate-slide-in {
            animation: slide-in 0.6s ease-out forwards;
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 3s ease infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Scroll reveal animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Dark mode styles */
        .dark {
            background-color: #0f172a;
            color: #e2e8f0;
        }

        .dark .bg-white {
            background-color: #1e293b !important;
        }

        .dark .bg-gray-50 {
            background-color: #0f172a !important;
        }

        .dark .bg-gray-100 {
            background-color: #1e293b !important;
        }

        .dark .text-gray-900 {
            color: #f1f5f9 !important;
        }

        .dark .text-gray-800 {
            color: #e2e8f0 !important;
        }

        .dark .text-gray-700 {
            color: #cbd5e1 !important;
        }

        .dark .text-gray-600 {
            color: #94a3b8 !important;
        }

        .dark .border-gray-200 {
            border-color: #334155 !important;
        }

        .dark .border-gray-300 {
            border-color: #475569 !important;
        }

        .dark .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.3) !important;
        }

        .dark .bg-gradient-to-b.from-gray-50.to-white {
            background: linear-gradient(to bottom, #1e293b, #0f172a) !important;
        }

        .dark .bg-gradient-to-b.from-gray-50 {
            background: #1e293b !important;
        }

        .dark .bg-gray-200 {
            background-color: #334155 !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-900 text-gray-100 overflow-x-hidden dark">
    <!-- Loading Spinner -->
    <div id="loading"
        class="fixed inset-0 bg-gray-900 z-[100] flex items-center justify-center transition-opacity duration-500">
        <div class="w-16 h-16 border-4 border-blue-800 border-t-blue-500 rounded-full animate-spin"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full bg-gray-900/95 backdrop-blur-md border-b border-gray-800 z-50 shadow-lg transition-all duration-300"
        x-data="{ scrolled: false, mobileMenuOpen: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
        :class="{ 'py-2 shadow-xl bg-gray-900/98': scrolled, 'py-0': !scrolled }">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo with animation -->
                <a href="#home" class="flex items-center space-x-3 group">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
                        <i class="fas fa-shield-alt text-white text-lg"></i>
                    </div>
                    <span
                        class="text-xl font-bold bg-gradient-to-r text-white from-blue-400 to-purple-400 bg-clip-text text-transparent group-hover:from-blue-300 group-hover:to-purple-300 transition-all duration-300">
                        {{ __('VPN Premium') }}
                    </span>
                </a>

                <!-- Desktop Navigation with hover effects -->
                <div class="hidden md:flex items-center space-x-8">
                    @foreach (['home' => __('Home'), 'features' => __('Features'), 'protocols' => __('Protocols'), 'setup' => __('Setup')] as $section => $label)
                        <a href="#{{ $section }}"
                            class="text-gray-300 hover:text-blue-400 font-medium transition-all duration-300 relative after:content-[''] after:absolute after:w-full after:scale-x-0 after:h-0.5 after:bottom-[-4px] after:left-0 after:bg-blue-500 after:origin-bottom-right after:transition-transform after:duration-300 hover:after:scale-x-100 hover:after:origin-bottom-left">
                            {{ $label }}
                        </a>
                    @endforeach

                    @if (Route::has('module.vpn.web.users.peers'))
                        <a href="{{ route('module.vpn.web.users.peers') }}"
                            class="group bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-900/50">
                            <i class="fas fa-rocket mr-2"></i>{{ __('Get Started') }}
                            <i
                                class="fas fa-arrow-right ml-2 text-sm opacity-0 -translate-x-2 transition-all duration-300 group-hover:opacity-100 group-hover:translate-x-0"></i>
                        </a>
                    @endif
                </div>

                <!-- Mobile Menu Button with animation -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden text-gray-300 hover:text-blue-400 transition-colors duration-300">
                    <i class="fas fa-bars text-xl" :class="{ 'fa-times': mobileMenuOpen }"></i>
                </button>
            </div>

            <!-- Mobile Navigation with slide animation -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4" class="md:hidden pb-4 space-y-2"
                @click.away="mobileMenuOpen = false">
                @foreach (['home' => __('Home'), 'features' => __('Features'), 'protocols' => __('Protocols'), 'setup' => __('Setup')] as $section => $label)
                    <a href="#{{ $section }}" @click="mobileMenuOpen = false"
                        class="block text-gray-300 hover:text-blue-400 font-medium py-2 px-4 rounded-lg hover:bg-gray-800 transition-all duration-300">
                        {{ $label }}
                    </a>
                @endforeach

                @if (Route::has('module.vpn.web.users.peers'))
                    <a href="{{ route('module.vpn.web.users.peers') }}" @click="mobileMenuOpen = false"
                        class="block bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-2.5 px-4 rounded-lg text-center transition-all duration-300 mt-2">
                        <i class="fas fa-rocket mr-2"></i>{{ __('Get Started') }}
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section with particles effect -->
    <section id="home"
        class="relative pt-24 pb-16 md:pt-32 md:pb-24 bg-gradient-to-br from-gray-900 via-blue-950 to-purple-950 text-white overflow-hidden">
        <!-- Animated background particles -->
        <div class="absolute inset-0 opacity-30">
            <div
                class="absolute top-20 left-10 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl animate-blob">
            </div>
            <div
                class="absolute top-40 right-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute bottom-20 left-1/2 w-72 h-72 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000">
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Protocol Badges with animation -->
                <div class="flex flex-wrap gap-3 mb-8 justify-center md:justify-start animate-slide-in">
                    <span
                        class="bg-yellow-600/30 text-yellow-400 border border-yellow-600/50 px-4 py-2 rounded-full text-sm font-medium hover:scale-105 transition-transform duration-300 cursor-default">
                        <i class="fas fa-bolt mr-2"></i>{{ __('WireGuard') }}
                    </span>
                    <span
                        class="bg-blue-600/30 text-blue-400 border border-blue-600/50 px-4 py-2 rounded-full text-sm font-medium hover:scale-105 transition-transform duration-300 cursor-default">
                        <i class="fas fa-random mr-2"></i>{{ __('HTTP/HTTPS Proxy') }}
                    </span>
                    <span
                        class="bg-purple-600/30 text-purple-400 border border-purple-600/50 px-4 py-2 rounded-full text-sm font-medium hover:scale-105 transition-transform duration-300 cursor-default">
                        <i class="fas fa-exchange-alt mr-2"></i>{{ __('SOCKS5') }}
                    </span>
                </div>

                <!-- Main Title with gradient animation -->
                <h1
                    class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 text-center md:text-left leading-tight animate-slide-in text-white">
                    {{ __('Secure Your Digital Life with') }}
                    <span
                        class="bg-gradient-to-r from-blue-400 via-purple-400 to-blue-400 bg-clip-text text-transparent animate-gradient">
                        {{ __('Premium VPN') }}
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="text-lg md:text-xl text-gray-300 mb-10 text-center md:text-left max-w-2xl animate-slide-in">
                    {{ __('Connect securely, fast, and anonymously to the internet from any device. Our VPN uses cutting-edge technology to protect your privacy.') }}
                </p>

                <!-- Features Grid with staggered animation -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="group bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700 transition-all duration-500 hover:border-gray-600 hover:shadow-2xl hover:-translate-y-2 hover:bg-gray-800/70"
                        x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition.delay.0>
                        <div
                            class="w-12 h-12 bg-emerald-900/50 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 group-hover:bg-emerald-800/50">
                            <i class="fas fa-lock text-emerald-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">{{ __('Military-Grade Encryption') }}</h3>
                        <p class="text-gray-400">{{ __('AES-256 encryption for maximum security') }}</p>
                    </div>

                    <div class="group bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700 transition-all duration-500 hover:border-gray-600 hover:shadow-2xl hover:-translate-y-2 hover:bg-gray-800/70"
                        x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition.delay.100>
                        <div
                            class="w-12 h-12 bg-blue-900/50 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 group-hover:bg-blue-800/50">
                            <i class="fas fa-tachometer-alt text-blue-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">{{ __('Lightning Fast Speed') }}</h3>
                        <p class="text-gray-400">{{ __('Optimized WireGuard for best performance') }}</p>
                    </div>

                    <div class="group bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700 transition-all duration-500 hover:border-gray-600 hover:shadow-2xl hover:-translate-y-2 hover:bg-gray-800/70"
                        x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition.delay.200>
                        <div
                            class="w-12 h-12 bg-purple-900/50 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 group-hover:bg-purple-800/50">
                            <i class="fas fa-globe text-purple-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">{{ __('Global Coverage') }}</h3>
                        <p class="text-gray-400">{{ __('Servers in 30+ countries worldwide') }}</p>
                    </div>
                </div>

                <!-- CTA Buttons with hover effects -->
                <div class="flex flex-col sm:flex-row gap-4 animate-slide-in">
                    @if (Route::has('module.vpn.web.users.peers'))
                        <a href="{{ route('module.vpn.web.users.peers') }}"
                            class="group relative bg-gradient-to-r from-emerald-600 to-green-700 hover:from-emerald-700 hover:to-green-800 text-white px-8 py-4 rounded-lg font-bold text-lg flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-900/50 overflow-hidden">
                            <span
                                class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                            <i class="fas fa-rocket mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                            <span class="relative">{{ __('Start Now - It\'s Free') }}</span>
                            <i
                                class="fas fa-arrow-right ml-3 opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
                        </a>
                    @endif

                    <a href="#protocols"
                        class="group bg-gray-800/50 hover:bg-gray-800 text-white px-8 py-4 rounded-lg font-bold text-lg border border-gray-700 flex items-center justify-center transition-all duration-300 hover:shadow-lg">
                        <i class="fas fa-info-circle mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                        <span>{{ __('Learn More') }}</span>
                        <i
                            class="fas fa-chevron-right ml-3 text-sm opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Floating shape -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full h-auto">
                <path fill="#0f172a" fill-opacity="1"
                    d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>
    </section>

    <!-- Features Section with counters -->
    <section id="features" class="py-16 md:py-24 bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white">
                    {{ __('Why Choose Our VPN Service?') }}
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                    {{ __('Everything you need for complete online security and privacy') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Privacy Card -->
                <div class="group bg-gray-800 rounded-2xl p-8 border border-gray-700 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 reveal"
                    x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition.delay.0>
                    <div
                        class="w-16 h-16 bg-emerald-900/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 group-hover:rotate-3">
                        <i class="fas fa-user-secret text-emerald-400 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">{{ __('Complete Privacy Protection') }}</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('No activity logs kept') }}</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('Hidden IP address') }}</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('DNS leak protection') }}</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('Kill switch included') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Security Card -->
                <div class="group bg-gray-800 rounded-2xl p-8 border border-gray-700 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 reveal"
                    x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition.delay.100>
                    <div
                        class="w-16 h-16 bg-blue-900/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 group-hover:rotate-3">
                        <i class="fas fa-shield-alt text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">{{ __('Advanced Security Features') }}</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('AES-256 encryption') }}</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('Automatic WiFi protection') }}</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('Split tunneling support') }}</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('Ad & malware blocker') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Speed Card -->
                <div class="group bg-gray-800 rounded-2xl p-8 border border-gray-700 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 reveal"
                    x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition.delay.200>
                    <div
                        class="w-16 h-16 bg-yellow-900/50 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 group-hover:rotate-3">
                        <i class="fas fa-bolt text-yellow-400 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">{{ __('Unlimited High-Speed Connection') }}</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('Optimized WireGuard protocol') }}</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('Unlimited bandwidth') }}</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('Low-latency servers') }}</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i
                                class="fas fa-check text-emerald-500 mt-1 mr-3 group-hover/item:scale-110 transition-transform duration-300"></i>
                            <span class="text-gray-300">{{ __('P2P/Torrent allowed') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Protocols Section with interactive cards -->
    <section id="protocols" class="py-16 md:py-24 bg-gray-950">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white">
                    {{ __('Supported Protocols') }}
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                    {{ __('Choose the perfect protocol for your specific needs') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- WireGuard Card -->
                <div class="group bg-gray-800 rounded-2xl p-8 shadow-xl border-l-4 border-yellow-600 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 cursor-pointer reveal"
                    x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-yellow-900/50 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300"
                            :class="{ 'rotate-12': hover }">
                            <i class="fas fa-bolt text-yellow-400 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">{{ __('WireGuard® VPN') }}</h3>
                    </div>
                    <p class="text-gray-400 mb-6">
                        {{ __('Next-generation VPN protocol offering superior speed and security. Simpler, faster, and more secure than traditional VPN protocols.') }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div
                            class="text-center p-3 rounded-lg bg-gray-900 group-hover:bg-gray-700 transition-colors duration-300">
                            <div class="text-lg font-bold text-white">10x</div>
                            <div class="text-xs text-gray-400">{{ __('Faster than OpenVPN') }}</div>
                        </div>
                        <div
                            class="text-center p-3 rounded-lg bg-gray-900 group-hover:bg-gray-700 transition-colors duration-300">
                            <div class="text-lg font-bold text-white">4,000</div>
                            <div class="text-xs text-gray-400">{{ __('Lines of code') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Proxy Card -->
                <div class="group bg-gray-800 rounded-2xl p-8 shadow-xl border-l-4 border-blue-600 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 cursor-pointer reveal"
                    x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-blue-900/50 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300"
                            :class="{ 'rotate-12': hover }">
                            <i class="fas fa-random text-blue-400 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">{{ __('HTTP/HTTPS Proxy') }}</h3>
                    </div>
                    <p class="text-gray-400 mb-6">
                        {{ __('Perfect for web browsing and specific applications. Route only selected traffic through our secure proxy servers.') }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div
                            class="text-center p-3 rounded-lg bg-gray-900 group-hover:bg-gray-700 transition-colors duration-300">
                            <div class="text-lg font-bold text-white">{{ __('HTTP/1.1') }}</div>
                            <div class="text-xs text-gray-400">{{ __('HTTP/2 Support') }}</div>
                        </div>
                        <div
                            class="text-center p-3 rounded-lg bg-gray-900 group-hover:bg-gray-700 transition-colors duration-300">
                            <div class="text-lg font-bold text-white">{{ __('SSL') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Encryption') }}</div>
                        </div>
                    </div>
                </div>

                <!-- SOCKS5 Card -->
                <div class="group bg-gray-800 rounded-2xl p-8 shadow-xl border-l-4 border-purple-600 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 cursor-pointer reveal"
                    x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-purple-900/50 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300"
                            :class="{ 'rotate-12': hover }">
                            <i class="fas fa-exchange-alt text-purple-400 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">{{ __('SOCKS5 Proxy') }}</h3>
                    </div>
                    <p class="text-gray-400 mb-6">
                        {{ __('Ideal for torrenting, gaming, and applications that require UDP support. Faster than traditional VPN for specific use cases.') }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div
                            class="text-center p-3 rounded-lg bg-gray-900 group-hover:bg-gray-700 transition-colors duration-300">
                            <div class="text-lg font-bold text-white">{{ __('UDP/TCP') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Protocol Support') }}</div>
                        </div>
                        <div
                            class="text-center p-3 rounded-lg bg-gray-900 group-hover:bg-gray-700 transition-colors duration-300">
                            <div class="text-lg font-bold text-white">{{ __('No Auth') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Authentication') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Setup Section with progress indicator -->
    <section id="setup" class="py-16 md:py-24 bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white">
                    {{ __('Easy Setup in 3 Steps') }}
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                    {{ __('Get started with our VPN service in minutes') }}
                </p>
            </div>

            <div class="relative mb-12">
                <!-- Progress line -->
                <div class="hidden md:block absolute top-20 left-0 right-0 h-1 bg-gray-700">
                    <div class="h-full bg-gradient-to-r from-blue-600 to-purple-600 rounded-full transition-all duration-500"
                        x-data="{ width: 0 }" x-init="const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    width = 100;
                                }
                            });
                        });
                        observer.observe($el);" :style="`width: ${width}%`">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                    <!-- Step 1 -->
                    <div class="text-center group reveal" x-data="{ shown: false }" x-intersect="shown = true">
                        <div class="relative inline-block">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300 relative z-10"
                                :class="{ 'animate-pulse-glow': shown }">
                                1
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">{{ __('Create Your Device') }}</h3>
                        <p class="text-gray-400">
                            {{ __('Log in to your dashboard and create a new device configuration. Give it a name you\'ll remember.') }}
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center group reveal" x-data="{ shown: false }" x-intersect="shown = true">
                        <div class="relative inline-block">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300 relative z-10"
                                :class="{ 'animate-pulse-glow': shown }">
                                2
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">{{ __('Download Configuration') }}</h3>
                        <p class="text-gray-400">
                            {{ __('Download the .conf file or scan the QR code with the WireGuard app on your smartphone.') }}
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center group reveal" x-data="{ shown: false }" x-intersect="shown = true">
                        <div class="relative inline-block">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300 relative z-10"
                                :class="{ 'animate-pulse-glow': shown }">
                                3
                            </div>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">{{ __('Connect & Enjoy') }}</h3>
                        <p class="text-gray-400">
                            {{ __('Import the configuration into WireGuard and tap connect. Your connection is now secure and private!') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Final CTA with animation -->
            <div class="text-center reveal">
                @if (Route::has('module.vpn.web.users.peers'))
                    <a href="{{ route('module.vpn.web.users.peers') }}"
                        class="group inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-900/50">
                        <i class="fas fa-play-circle mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                        <span>{{ __('Start Securing Your Connection') }}</span>
                        <i
                            class="fas fa-arrow-right ml-3 opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-blue-900 to-purple-900 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="text-4xl mb-3 text-white group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="text-3xl font-bold mb-1 text-white">30+</div>
                    <div class="text-white/80">{{ __('Server Locations') }}</div>
                </div>
                <div class="text-center group">
                    <div class="text-4xl mb-3 text-white group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="text-3xl font-bold mb-1 text-white">10K+</div>
                    <div class="text-white/80">{{ __('Happy Users') }}</div>
                </div>
                <div class="text-center group">
                    <div class="text-4xl mb-3 text-white group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="text-3xl font-bold mb-1 text-white">1Gbps</div>
                    <div class="text-white/80">{{ __('Max Speed') }}</div>
                </div>
                <div class="text-center group">
                    <div class="text-4xl mb-3 text-white group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="text-3xl font-bold mb-1 text-white">99.9%</div>
                    <div class="text-white/80">{{ __('Uptime') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 md:py-24 bg-gray-950">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white">
                    {{ __('What Our Users Say') }}
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                    {{ __('Trusted by thousands of users worldwide') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div
                    class="bg-gray-800 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 reveal">
                    <div class="flex items-center mb-4">
                        <img src="https://i.pravatar.cc/150?img=1" alt="Alex Johnson"
                            class="w-12 h-12 rounded-full mr-4 border-2 border-gray-700">
                        <div>
                            <h4 class="font-bold text-white">Alex Johnson</h4>
                            <p class="text-sm text-gray-400">{{ __('Software Developer') }}</p>
                        </div>
                    </div>
                    <div class="flex mb-3">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-yellow-500 text-sm"></i>
                        @endfor
                    </div>
                    <p class="text-gray-300 italic">
                        "{{ __('The WireGuard implementation is blazing fast. I\'ve tried many VPNs and this one is by far the best for development work.') }}"
                    </p>
                </div>

                <!-- Testimonial 2 -->
                <div
                    class="bg-gray-800 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 reveal">
                    <div class="flex items-center mb-4">
                        <img src="https://i.pravatar.cc/150?img=5" alt="Sarah Chen"
                            class="w-12 h-12 rounded-full mr-4 border-2 border-gray-700">
                        <div>
                            <h4 class="font-bold text-white">Sarah Chen</h4>
                            <p class="text-sm text-gray-400">{{ __('Digital Nomad') }}</p>
                        </div>
                    </div>
                    <div class="flex mb-3">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-yellow-500 text-sm"></i>
                        @endfor
                    </div>
                    <p class="text-gray-300 italic">
                        "{{ __('Being able to switch between different protocols is amazing. The SOCKS5 proxy works perfectly for my torrenting needs.') }}"
                    </p>
                </div>

                <!-- Testimonial 3 -->
                <div
                    class="bg-gray-800 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 reveal">
                    <div class="flex items-center mb-4">
                        <img src="https://i.pravatar.cc/150?img=8" alt="Mike Williams"
                            class="w-12 h-12 rounded-full mr-4 border-2 border-gray-700">
                        <div>
                            <h4 class="font-bold text-white">Mike Williams</h4>
                            <p class="text-sm text-gray-400">{{ __('Security Consultant') }}</p>
                        </div>
                    </div>
                    <div class="flex mb-3">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-yellow-500 text-sm"></i>
                        @endfor
                    </div>
                    <p class="text-gray-300 italic">
                        "{{ __('The no-logs policy and kill switch give me peace of mind. This is exactly what I recommend to my clients.') }}"
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 md:py-24 bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white">
                    {{ __('Frequently Asked Questions') }}
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                    {{ __('Got questions? We\'ve got answers') }}
                </p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <!-- FAQ 1 -->
                <div class="border border-gray-700 rounded-lg overflow-hidden bg-gray-800" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full px-6 py-4 text-left bg-gray-800 hover:bg-gray-700 transition-colors duration-300 flex justify-between items-center">
                        <span class="font-semibold text-white">{{ __('What is WireGuard?') }}</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 py-4 bg-gray-800 border-t border-gray-700">
                        <p class="text-gray-300">
                            {{ __('WireGuard is a modern VPN protocol that is faster, simpler, and more secure than traditional protocols like OpenVPN or IPSec.') }}
                        </p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border border-gray-700 rounded-lg overflow-hidden bg-gray-800" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full px-6 py-4 text-left bg-gray-800 hover:bg-gray-700 transition-colors duration-300 flex justify-between items-center">
                        <span class="font-semibold text-white">{{ __('Do you keep logs?') }}</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 py-4 bg-gray-800 border-t border-gray-700">
                        <p class="text-gray-300">
                            {{ __('No, we operate under a strict no-logs policy. We do not track or store any of your online activities.') }}
                        </p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-gray-700 rounded-lg overflow-hidden bg-gray-800" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full px-6 py-4 text-left bg-gray-800 hover:bg-gray-700 transition-colors duration-300 flex justify-between items-center">
                        <span class="font-semibold text-white">{{ __('How many devices can I use?') }}</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 py-4 bg-gray-800 border-t border-gray-700">
                        <p class="text-gray-300">
                            {{ __('You can connect unlimited devices simultaneously with a single account.') }}</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border border-gray-700 rounded-lg overflow-hidden bg-gray-800" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full px-6 py-4 text-left bg-gray-800 hover:bg-gray-700 transition-colors duration-300 flex justify-between items-center">
                        <span class="font-semibold text-white">{{ __('Is there a money-back guarantee?') }}</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-6 py-4 bg-gray-800 border-t border-gray-700">
                        <p class="text-gray-300">
                            {{ __('Yes, we offer a 30-day money-back guarantee on all our plans.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer with modern design -->
    <footer class="bg-gray-950 text-white pt-12 pb-8 border-t border-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-8">
                <!-- Brand -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-4 group">
                        <div
                            class="w-9 h-9 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <span
                            class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                            {{ __('VPN Premium') }}
                        </span>
                    </div>
                    <p class="text-gray-400 mb-4 text-sm max-w-md">
                        {{ __('Secure your digital life with our premium VPN service featuring WireGuard, Proxy, and SOCKS5 support.') }}
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://github.com/elyerrlabs/vpn" target="_blank" rel="noopener noreferrer"
                            class="text-gray-400 hover:text-white transition-all duration-300 hover:scale-110 hover:-translate-y-1"
                            title="GitHub">
                            <i class="fab fa-github text-xl"></i>
                        </a>
                        <a href="https://youtube.com/@elyerrlabs" target="_blank" rel="noopener noreferrer"
                            class="text-gray-400 hover:text-white transition-all duration-300 hover:scale-110 hover:-translate-y-1"
                            title="YouTube">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                        <a href="https://packagist.org/packages/elyerr" target="_blank" rel="noopener noreferrer"
                            class="text-gray-400 hover:text-white transition-all duration-300 hover:scale-110 hover:-translate-y-1"
                            title="Packagist">
                            <i class="fab fa-php text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-white">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#home"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fas fa-home mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Home') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="#features"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fas fa-star mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Features') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="#protocols"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fas fa-network-wired mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Protocols') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="#setup"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fas fa-cogs mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Setup Guide') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-white">{{ __('Resources') }}</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="https://github.com/elyerrlabs/vpn" target="_blank" rel="noopener noreferrer"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fab fa-github mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('GitHub Repository') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://youtube.com/@elyerrlabs" target="_blank" rel="noopener noreferrer"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fab fa-youtube mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('YouTube Tutorials') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://packagist.org/packages/elyerr" target="_blank" rel="noopener noreferrer"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fab fa-php mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Packagist Packages') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/elyerrlabs/vpn/issues" target="_blank"
                                rel="noopener noreferrer"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fas fa-bug mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Report Issues') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-white">{{ __('Legal') }}</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fas fa-file-contract mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Terms of Service') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fas fa-shield-alt mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Privacy Policy') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fas fa-cookie mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Cookie Policy') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-400 hover:text-white transition-all duration-300 flex items-center group/link">
                                <i
                                    class="fas fa-balance-scale mr-2 text-sm group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                <span>{{ __('Legal Notice') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="border-t border-gray-800 pt-8 pb-4">
                <div class="max-w-md mx-auto text-center">
                    <h4 class="text-lg font-bold mb-2 text-white">{{ __('Subscribe to our newsletter') }}</h4>
                    <p class="text-gray-400 text-sm mb-4">{{ __('Get the latest updates and offers') }}</p>
                    <form class="flex gap-2" @submit.prevent="alert('Newsletter subscription coming soon!')">
                        <input type="email" placeholder="{{ __('Your email address') }}"
                            class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 transition-colors duration-300">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-300 hover:scale-105">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400 mb-2">
                    &copy; {{ date('Y') }} {{ __('VPN Premium Service. All rights reserved.') }}
                </p>
                <p class="text-gray-500 text-sm mb-2">
                    {{ __('WireGuard® is a registered trademark of Jason A. Donenfeld.') }}
                </p>
                <p class="text-gray-500 text-sm">
                    {{ __('Developed with') }} <i class="fas fa-heart text-red-500 animate-pulse"></i>
                    {{ __('by elyerrlabs') }}
                </p>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <button id="backToTop"
        class="fixed bottom-8 right-8 w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 opacity-0 invisible hover:scale-110 hover:shadow-xl"
        x-data="{ visible: false }" x-init="window.addEventListener('scroll', () => { visible = window.scrollY > 500 })"
        :class="{ 'opacity-100 visible': visible, 'opacity-0 invisible': !visible }"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Alpine.js for interactivity -->
    <script nonce="{{ $nonce }}" src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Custom JavaScript -->
    <script nonce="{{ $nonce }}">
        // Hide loading spinner
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loading').style.opacity = '0';
                setTimeout(function() {
                    document.getElementById('loading').style.display = 'none';
                }, 500);
            }, 500);
        });

        // Scroll reveal animation
        function reveal() {
            const reveals = document.querySelectorAll('.reveal');

            reveals.forEach(element => {
                const windowHeight = window.innerHeight;
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;

                if (elementTop < windowHeight - elementVisible) {
                    element.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', reveal);
        window.addEventListener('load', reveal);

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const navHeight = document.querySelector('nav').offsetHeight;

                    window.scrollTo({
                        top: targetElement.offsetTop - navHeight,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    if (window.Alpine) {
                        const nav = document.querySelector('[x-data]').__x;
                        if (nav && nav.mobileMenuOpen) {
                            nav.mobileMenuOpen = false;
                        }
                    }
                }
            });
        });

        // Update active nav link on scroll
        const sections = document.querySelectorAll('section[id]');

        function updateActiveNavLink() {
            const scrollY = window.scrollY;
            const navHeight = document.querySelector('nav').offsetHeight;

            sections.forEach(section => {
                const sectionTop = section.offsetTop - navHeight - 100;
                const sectionBottom = sectionTop + section.offsetHeight;
                const sectionId = section.getAttribute('id');

                const navLink = document.querySelector(`a[href="#${sectionId}"]`);

                if (navLink) {
                    if (scrollY > sectionTop && scrollY < sectionBottom) {
                        navLink.classList.add('text-blue-400');
                    } else {
                        navLink.classList.remove('text-blue-400');
                    }
                }
            });
        }

        window.addEventListener('scroll', updateActiveNavLink);
        window.addEventListener('load', updateActiveNavLink);
    </script>
</body>

</html>
