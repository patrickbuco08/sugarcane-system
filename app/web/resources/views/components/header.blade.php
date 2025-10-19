<header class="bg-theme-primary shadow" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-8">
                <h1 class="text-xl font-bold text-sugarcane-text">
                    {{ $title ?? config('app.title') }}
                </h1>

                @auth
                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('dashboard') }}"
                            class="text-sugarcane-text hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'font-semibold' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('weekly-prices.index') }}"
                            class="text-sugarcane-text hover:text-white transition-colors {{ request()->routeIs('weekly-prices.*') ? 'font-semibold' : '' }}">
                            Weekly Prices
                        </a>
                        <a href="{{ route('harvest-batches.index') }}"
                            class="text-sugarcane-text hover:text-white transition-colors {{ request()->routeIs('harvest-batches.*') ? 'font-semibold' : '' }}">
                            Harvest Batches
                        </a>
                        <a href="{{ route('maturities.index') }}"
                            class="text-sugarcane-text hover:text-white transition-colors {{ request()->routeIs('maturities.*') ? 'font-semibold' : '' }}">
                            Maturities
                        </a>
                        <a href="{{ route('forecast.index') }}"
                            class="text-sugarcane-text hover:text-white transition-colors {{ request()->routeIs('forecast.index') ? 'font-semibold' : '' }}">
                            Forecast
                        </a>
                        @can('view configurations')
                            <a href="{{ route('configurations.index') }}"
                                class="text-sugarcane-text hover:text-white transition-colors {{ request()->routeIs('configurations.*') ? 'font-semibold' : '' }}">
                                Configurations
                            </a>
                        @endcan

                        <a href="http://sugarcane.local/" rel="noopener noreferrer"
                            class="text-sugarcane-text hover:text-white transition-colors">
                            Scan
                        </a>
                    </nav>
                @endauth
            </div>

            @auth
                <div class="flex items-center space-x-4">
                    <!-- User Avatar Dropdown -->
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <div>
                            <button type="button" @click="open = !open"
                                class="flex items-center justify-center rounded-md px-2 bg-white text-theme-primary font-semibold text-base hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-theme-primary"
                                id="user-menu-button" aria-expanded="true" aria-haspopup="true">
                                @php
                                    $name = auth()->user()->name;
                                    $words = explode(' ', $name);
                                    $initials = '';
                                    foreach ($words as $word) {
                                        $initials .= strtoupper(substr($word, 0, 1));
                                    }
                                    $initials = substr($initials, 0, 2);
                                @endphp
                                {{ $initials }}
                            </button>
                        </div>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-hidden"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <div class="py-1" role="none">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" role="none">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50"
                                        role="menuitem" tabindex="-1">Sign out</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden text-sugarcane-text hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-theme-primary rounded-md p-2">
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endauth
        </div>

        @auth
            <!-- Mobile Navigation Menu -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition
                class="md:hidden mt-4 pb-4 border-t border-theme-secondary pt-4">
                <nav class="flex flex-col space-y-3">
                    <a href="{{ route('dashboard') }}"
                        class="text-sugarcane-text hover:text-white transition-colors px-2 py-2 rounded {{ request()->routeIs('dashboard') ? 'font-semibold bg-theme-secondary' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('weekly-prices.index') }}"
                        class="text-sugarcane-text hover:text-white transition-colors px-2 py-2 rounded {{ request()->routeIs('weekly-prices.*') ? 'font-semibold bg-theme-secondary' : '' }}">
                        Weekly Prices
                    </a>
                    <a href="{{ route('harvest-batches.index') }}"
                        class="text-sugarcane-text hover:text-white transition-colors px-2 py-2 rounded {{ request()->routeIs('harvest-batches.*') ? 'font-semibold bg-theme-secondary' : '' }}">
                        Harvest Batches
                    </a>
                    <a href="{{ route('maturities.index') }}"
                        class="text-sugarcane-text hover:text-white transition-colors px-2 py-2 rounded {{ request()->routeIs('maturities.*') ? 'font-semibold bg-theme-secondary' : '' }}">
                        Maturities
                    </a>
                    <a href="{{ route('forecast.index') }}"
                        class="text-sugarcane-text hover:text-white transition-colors px-2 py-2 rounded {{ request()->routeIs('forecast.index') ? 'font-semibold bg-theme-secondary' : '' }}">
                        Forecasts
                    </a>
                    @can('view configurations')
                        <a href="{{ route('configurations.index') }}"
                            class="text-sugarcane-text hover:text-white transition-colors px-2 py-2 rounded {{ request()->routeIs('configurations.*') ? 'font-semibold bg-theme-secondary' : '' }}">
                            Configurations
                        </a>
                    @endcan
                </nav>
            </div>
        @endauth
    </div>
</header>
