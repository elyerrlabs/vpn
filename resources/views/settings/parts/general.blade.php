@extends('Vpn::settings.main')

@section('form')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('VPN Configuration') }}
                </h2>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Settings') }}</span>
                </div>
            </div>
            <p class="text-gray-600 dark:text-gray-300">
                {{ __('Configure VPN limits for different user tiers. These settings control server access and device limits.') }}
            </p>
        </div>

        <!-- Free Plan -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="bg-linear-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 dark:text-gray-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Free Plan') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Basic VPN access') }}</p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm font-medium">
                        {{ __('Default') }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Servers Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-server text-indigo-500"></i>
                                <span>{{ __('Server Access') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Number of available servers') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[servers][free]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.servers.free', 0) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Devices Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-mobile-alt text-indigo-500"></i>
                                <span>{{ __('WireGuard Peers') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Maximum simultaneous peers/devices') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[peers][free]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.peers.free', 2) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Plan -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="bg-linear-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Basic Plan') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Enhanced VPN access') }}</p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-sm font-medium">
                        {{ __('Popular') }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Servers Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-server text-blue-500"></i>
                                <span>{{ __('Server Access') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Number of available servers') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[servers][basic]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.servers.basic', 1) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Devices Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-mobile-alt text-blue-500"></i>
                                <span>{{ __('WireGuard Peers') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Maximum simultaneous peers/devices') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[peers][basic]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.peers.basic', 5) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Intermediate Plan -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="bg-linear-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-crown text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Intermediate Plan') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Premium VPN access') }}</p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-sm font-medium">
                        {{ __('Pro') }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Servers Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-server text-purple-500"></i>
                                <span>{{ __('Server Access') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Number of available servers') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[servers][intermediate]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.servers.intermediate', 2) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Devices Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-mobile-alt text-purple-500"></i>
                                <span>{{ __('WireGuard Peers') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Maximum simultaneous peers/devices') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[peers][intermediate]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.peers.intermediate', 10) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Plan -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="bg-linear-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-rocket text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Advanced Plan') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Enterprise VPN access') }}</p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-sm font-medium">
                        {{ __('Business') }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Servers Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-server text-green-500"></i>
                                <span>{{ __('Server Access') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Number of available servers') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[servers][advanced]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.servers.advanced', 3) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Devices Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-mobile-alt text-green-500"></i>
                                <span>{{ __('WireGuard Peers') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Maximum simultaneous peers/devices') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[peers][advanced]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 dark:focus:ring-green-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.peers.advanced', 20) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Plan -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="bg-linear-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-gem text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Professional Plan') }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Ultimate VPN access') }}</p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-sm font-medium">
                        {{ __('Ultimate') }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Servers Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-server text-orange-500"></i>
                                <span>{{ __('Server Access') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Number of available servers') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[servers][professional]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.servers.professional', 5) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Devices Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-mobile-alt text-orange-500"></i>
                                <span>{{ __('WireGuard Peers') }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-400">{{ __('Maximum simultaneous peers/devices') }}</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="plans[peers][professional]" min="0"
                                class="w-full px-8 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 focus:border-transparent transition-all duration-200"
                                value="{{ config_module('plans.peers.professional', 20) }}">
                            <div class="absolute left-3 top-3 text-gray-400 dark:text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Box -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <div class="flex items-start space-x-3">
                <div class="shrink-0">
                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-xl mt-1"></i>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-2">
                        {{ __('Configuration Notes') }}</h4>
                    <ul class="space-y-2 text-blue-700 dark:text-blue-400 text-sm">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check mt-1"></i>
                            <span>{{ __('Set 0 to disable access for a specific plan') }}</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check mt-1"></i>
                            <span>{{ __('Server access controls which VPN servers users can connect to') }}</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check mt-1"></i>
                            <span>{{ __('WireGuard peers represent maximum simultaneous devices per user') }}</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-check mt-1"></i>
                            <span>{{ __('Changes take effect immediately after saving') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
