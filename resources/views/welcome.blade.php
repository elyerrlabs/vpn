<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Premium VPN - WireGuard + Proxy + SOCKS5') }}</title>
    <meta name="description"
        content="{{ __('Secure your internet connection with our premium VPN service featuring WireGuard, HTTP/HTTPS Proxy, and SOCKS5 protocols. Fast, secure, and anonymous browsing.') }}">

    @if (file_exists(public_path('third-party/vpn/favicon.png')))
        <link rel="icon" href="{{ asset('third-party/vpn/favicon.png') }}" type="image/png">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
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
                        },
                        accent: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(10px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/95 backdrop-blur-sm border-b border-gray-200 z-50 shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-primary-600 to-accent-600 rounded-lg flex items-center justify-center shadow-md">
                        <i class="fas fa-shield-alt text-white text-lg"></i>
                    </div>
                    <span
                        class="text-xl font-bold bg-gradient-to-r from-primary-700 to-accent-600 bg-clip-text text-transparent">
                        {{ __('VPN Premium') }}
                    </span>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home"
                        class="text-gray-600 hover:text-primary-600 font-medium transition-colors duration-200">
                        {{ __('Home') }}
                    </a>
                    <a href="#features"
                        class="text-gray-600 hover:text-primary-600 font-medium transition-colors duration-200">
                        {{ __('Features') }}
                    </a>
                    <a href="#protocols"
                        class="text-gray-600 hover:text-primary-600 font-medium transition-colors duration-200">
                        {{ __('Protocols') }}
                    </a>
                    <a href="#setup"
                        class="text-gray-600 hover:text-primary-600 font-medium transition-colors duration-200">
                        {{ __('Setup') }}
                    </a>

                    @if (Route::has('module.vpn.web.users.peers'))
                        <a href="{{ route('module.vpn.web.users.peers') }}"
                            class="bg-gradient-to-r from-primary-600 to-accent-500 hover:from-primary-700 hover:to-accent-600 text-white px-5 py-2.5 rounded-lg font-medium transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                            <i class="fas fa-rocket mr-2"></i>{{ __('Get Started') }}
                        </a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton" class="md:hidden text-gray-600 hover:text-primary-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="hidden md:hidden pb-4 space-y-2 animate-slide-up">
                <a href="#home"
                    class="block text-gray-600 hover:text-primary-600 font-medium py-2 px-4 rounded-lg hover:bg-gray-100 transition-colors">
                    {{ __('Home') }}
                </a>
                <a href="#features"
                    class="block text-gray-600 hover:text-primary-600 font-medium py-2 px-4 rounded-lg hover:bg-gray-100 transition-colors">
                    {{ __('Features') }}
                </a>
                <a href="#protocols"
                    class="block text-gray-600 hover:text-primary-600 font-medium py-2 px-4 rounded-lg hover:bg-gray-100 transition-colors">
                    {{ __('Protocols') }}
                </a>
                <a href="#setup"
                    class="block text-gray-600 hover:text-primary-600 font-medium py-2 px-4 rounded-lg hover:bg-gray-100 transition-colors">
                    {{ __('Setup') }}
                </a>

                @if (Route::has('module.vpn.web.users.peers'))
                    <a href="{{ route('module.vpn.web.users.peers') }}"
                        class="block bg-gradient-to-r from-primary-600 to-accent-500 hover:from-primary-700 hover:to-accent-600 text-white font-medium py-2.5 px-4 rounded-lg text-center transition-all duration-300 mt-2">
                        <i class="fas fa-rocket mr-2"></i>{{ __('Get Started') }}
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home"
        class="pt-24 pb-16 md:pt-32 md:pb-24 bg-gradient-to-br from-gray-900 via-primary-900 to-accent-900 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Protocol Badges -->
                <div class="flex flex-wrap gap-3 mb-8 justify-center md:justify-start">
                    <span
                        class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-bolt mr-2"></i>{{ __('WireGuard') }}
                    </span>
                    <span
                        class="bg-blue-500/20 text-blue-400 border border-blue-500/30 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-random mr-2"></i>{{ __('HTTP/HTTPS Proxy') }}
                    </span>
                    <span
                        class="bg-purple-500/20 text-purple-400 border border-purple-500/30 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-exchange-alt mr-2"></i>{{ __('SOCKS5') }}
                    </span>
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 text-center md:text-left leading-tight">
                    {{ __('Secure Your Digital Life with') }}
                    <span class="bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">
                        {{ __('Premium VPN') }}
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="text-lg md:text-xl text-gray-300 mb-10 text-center md:text-left max-w-2xl">
                    {{ __('Connect securely, fast, and anonymously to the internet from any device. Our VPN uses cutting-edge technology to protect your privacy.') }}
                </p>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 transition-all duration-300 hover:border-white/40 hover:shadow-xl hover:-translate-y-1">
                        <div class="w-12 h-12 bg-emerald-500/20 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-lock text-emerald-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">{{ __('Military-Grade Encryption') }}</h3>
                        <p class="text-gray-300">{{ __('AES-256 encryption for maximum security') }}</p>
                    </div>

                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 transition-all duration-300 hover:border-white/40 hover:shadow-xl hover:-translate-y-1">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-tachometer-alt text-blue-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">{{ __('Lightning Fast Speed') }}</h3>
                        <p class="text-gray-300">{{ __('Optimized WireGuard for best performance') }}</p>
                    </div>

                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 transition-all duration-300 hover:border-white/40 hover:shadow-xl hover:-translate-y-1">
                        <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-globe text-purple-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">{{ __('Global Coverage') }}</h3>
                        <p class="text-gray-300">{{ __('Servers in 30+ countries worldwide') }}</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    @if (Route::has('module.vpn.web.users.peers'))
                        <a href="{{ route('module.vpn.web.users.peers') }}"
                            class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-8 py-4 rounded-lg font-bold text-lg flex items-center justify-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                            <i class="fas fa-rocket mr-3"></i>{{ __('Start Now - It\'s Free') }}
                        </a>
                    @endif

                    <a href="#protocols"
                        class="bg-white/10 hover:bg-white/20 text-white px-8 py-4 rounded-lg font-bold text-lg border border-white/30 flex items-center justify-center transition-all duration-300 hover:shadow-lg">
                        <i class="fas fa-info-circle mr-3"></i>{{ __('Learn More') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 text-gray-900">
                {{ __('Why Choose Our VPN Service?') }}
            </h2>
            <p class="text-gray-600 text-center mb-12 max-w-2xl mx-auto text-lg">
                {{ __('Everything you need for complete online security and privacy') }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Privacy Card -->
                <div
                    class="bg-gradient-to-b from-gray-50 to-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-user-secret text-emerald-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">{{ __('Complete Privacy Protection') }}</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('No activity logs kept') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('Hidden IP address') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('DNS leak protection') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('Kill switch included') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Security Card -->
                <div
                    class="bg-gradient-to-b from-gray-50 to-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">{{ __('Advanced Security Features') }}</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('AES-256 encryption') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('Automatic WiFi protection') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('Split tunneling support') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('Ad & malware blocker') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Speed Card -->
                <div
                    class="bg-gradient-to-b from-gray-50 to-white rounded-2xl p-8 border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-bolt text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">{{ __('Unlimited High-Speed Connection') }}</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('Optimized WireGuard protocol') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('Unlimited bandwidth') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('Low-latency servers') }}</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-emerald-500 mt-1 mr-3"></i>
                            <span class="text-gray-700">{{ __('P2P/Torrent allowed') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Protocols Section -->
    <section id="protocols" class="py-16 md:py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 text-gray-900">
                {{ __('Supported Protocols') }}
            </h2>
            <p class="text-gray-600 text-center mb-12 max-w-2xl mx-auto text-lg">
                {{ __('Choose the perfect protocol for your specific needs') }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- WireGuard Card -->
                <div
                    class="bg-white rounded-2xl p-8 shadow-xl border-l-4 border-yellow-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-bolt text-yellow-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ __('WireGuard® VPN') }}</h3>
                    </div>
                    <p class="text-gray-600 mb-6">
                        {{ __('Next-generation VPN protocol offering superior speed and security. Simpler, faster, and more secure than traditional VPN protocols.') }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">10x</div>
                            <div class="text-sm text-gray-600">{{ __('Faster than OpenVPN') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">4,000</div>
                            <div class="text-sm text-gray-600">{{ __('Lines of code') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Proxy Card -->
                <div
                    class="bg-white rounded-2xl p-8 shadow-xl border-l-4 border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-random text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ __('HTTP/HTTPS Proxy') }}</h3>
                    </div>
                    <p class="text-gray-600 mb-6">
                        {{ __('Perfect for web browsing and specific applications. Route only selected traffic through our secure proxy servers.') }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-900">{{ __('HTTP/1.1') }}</div>
                            <div class="text-sm text-gray-600">{{ __('HTTP/2 Support') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-900">{{ __('SSL') }}</div>
                            <div class="text-sm text-gray-600">{{ __('Encryption') }}</div>
                        </div>
                    </div>
                </div>

                <!-- SOCKS5 Card -->
                <div
                    class="bg-white rounded-2xl p-8 shadow-xl border-l-4 border-purple-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-exchange-alt text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ __('SOCKS5 Proxy') }}</h3>
                    </div>
                    <p class="text-gray-600 mb-6">
                        {{ __('Ideal for torrenting, gaming, and applications that require UDP support. Faster than traditional VPN for specific use cases.') }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-900">{{ __('UDP/TCP') }}</div>
                            <div class="text-sm text-gray-600">{{ __('Protocol Support') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-900">{{ __('No Auth') }}</div>
                            <div class="text-sm text-gray-600">{{ __('Authentication') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Setup Section -->
    <section id="setup" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 text-gray-900">
                {{ __('Easy Setup in 3 Steps') }}
            </h2>
            <p class="text-gray-600 text-center mb-12 max-w-2xl mx-auto text-lg">
                {{ __('Get started with our VPN service in minutes') }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Step 1 -->
                <div class="text-center group">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-primary-500 to-accent-500 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
                        1
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">{{ __('Create Your Device') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Log in to your dashboard and create a new device configuration. Give it a name you\'ll remember.') }}
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center group">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-primary-500 to-accent-500 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
                        2
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">{{ __('Download Configuration') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Download the .conf file or scan the QR code with the WireGuard app on your smartphone.') }}
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center group">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-primary-500 to-accent-500 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6 shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
                        3
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">{{ __('Connect & Enjoy') }}</h3>
                    <p class="text-gray-600">
                        {{ __('Import the configuration into WireGuard and tap connect. Your connection is now secure and private!') }}
                    </p>
                </div>
            </div>

            <!-- Final CTA -->
            <div class="text-center">
                @if (Route::has('module.vpn.web.users.peers'))
                    <a href="{{ route('module.vpn.web.users.peers') }}"
                        class="inline-flex items-center bg-gradient-to-r from-primary-600 to-accent-500 hover:from-primary-700 hover:to-accent-600 text-white px-8 py-4 rounded-lg font-bold text-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                        <i class="fas fa-play-circle mr-3"></i>{{ __('Start Securing Your Connection') }}
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 to-gray-800 text-white pt-12 pb-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div
                            class="w-9 h-9 bg-gradient-to-br from-primary-600 to-accent-600 rounded-lg flex items-center justify-center shadow-md">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <span
                            class="text-xl font-bold bg-gradient-to-r from-primary-400 to-accent-400 bg-clip-text text-transparent">
                            {{ __('VPN Premium') }}
                        </span>
                    </div>
                    <p class="text-gray-400 mb-4 text-sm">
                        {{ __('Secure your digital life with our premium VPN service featuring WireGuard, Proxy, and SOCKS5 support.') }}
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://github.com/elyerrlabs/vpn" target="_blank"
                            class="text-gray-400 hover:text-white transition-colors duration-300" title="GitHub">
                            <i class="fab fa-github text-xl"></i>
                        </a>
                        <a href="https://youtube.com/@elyerrlabs" target="_blank"
                            class="text-gray-400 hover:text-white transition-colors duration-300" title="YouTube">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                        <a href="https://packagist.org/packages/elyerr" target="_blank"
                            class="text-gray-400 hover:text-white transition-colors duration-300" title="Packagist">
                            <i class="fab fa-php text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-white">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#home"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fas fa-home mr-2 text-sm"></i>{{ __('Home') }}
                            </a></li>
                        <li><a href="#features"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fas fa-star mr-2 text-sm"></i>{{ __('Features') }}
                            </a></li>
                        <li><a href="#protocols"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fas fa-network-wired mr-2 text-sm"></i>{{ __('Protocols') }}
                            </a></li>
                        <li><a href="#setup"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fas fa-cogs mr-2 text-sm"></i>{{ __('Setup Guide') }}
                            </a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-white">{{ __('Resources') }}</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="https://github.com/elyerrlabs/vpn" target="_blank"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fab fa-github mr-2 text-sm"></i>{{ __('GitHub Repository') }}
                            </a>
                        </li>
                        <li>
                            <a href="https://youtube.com/@elyerrlabs" target="_blank"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fab fa-youtube mr-2 text-sm"></i>{{ __('YouTube Tutorials') }}
                            </a>
                        </li>
                        <li>
                            <a href="https://packagist.org/packages/elyerr" target="_blank"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fab fa-php mr-2 text-sm"></i>{{ __('Packagist Packages') }}
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/elyerrlabs/vpn/issues" target="_blank"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fas fa-bug mr-2 text-sm"></i>{{ __('Report Issues') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-white">{{ __('Legal') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fas fa-file-contract mr-2 text-sm"></i>{{ __('Terms of Service') }}
                            </a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fas fa-shield-alt mr-2 text-sm"></i>{{ __('Privacy Policy') }}
                            </a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fas fa-cookie mr-2 text-sm"></i>{{ __('Cookie Policy') }}
                            </a></li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                                <i class="fas fa-balance-scale mr-2 text-sm"></i>{{ __('Legal Notice') }}
                            </a></li>
                    </ul>
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
                    {{ __('Developed with ❤️ by elyerrlabs') }}
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const menuButton = document.getElementById('mobileMenuButton');

            if (!mobileMenu.contains(event.target) && !menuButton.contains(event.target) && !mobileMenu.classList
                .contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobileMenu');
                    if (!mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }

                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Update mobile menu on resize
        window.addEventListener('resize', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            if (window.innerWidth > 768) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
