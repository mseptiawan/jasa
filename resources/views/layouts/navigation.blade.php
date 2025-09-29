<nav x-data="{ open: false }"
     class="sticky top-0 z-40 bg-white/90 backdrop-blur-sm w-full border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo-JasaReceh.png') }}"
                             alt="JasaReceh"
                             class="h-10 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')"
                                :active="request()->routeIs('dashboard')">
                        {{ __('Beranda') }}
                    </x-nav-link>

                    @auth
                        <!-- Customer & Seller Common Links -->
                        @if (in_array(auth()->user()->role, ['customer', 'seller']))
                            <x-nav-link :href="route('orders.index')"
                                        :active="request()->routeIs('orders.*')">
                                {{ __('Pesanan') }}
                                @if (auth()->user()->role === 'seller')
                                    @php
                                        $pendingCount = \App\Models\Order::whereHas(
                                            'service',
                                            fn($q) => $q->where('user_id', auth()->id()),
                                        )
                                            ->where('status', 'pending')
                                            ->count();
                                    @endphp
                                    @if ($pendingCount > 0)
                                        <span
                                              class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                                    @endif
                                @endif
                            </x-nav-link>
                            <x-nav-link :href="route('conversations.index')"
                                        :active="request()->routeIs('conversations.*')">
                                {{ __('Chat') }}
                            </x-nav-link>
                            <x-nav-link :href="route('services.favorites')"
                                        :active="request()->routeIs('services.favorites')">
                                {{ __('Favorit Saya') }}
                            </x-nav-link>
                        @endif

                        <!-- Seller Dropdown -->
                        @if (auth()->user()->role === 'seller' && \App\Models\Service::where('user_id', auth()->id())->count() > 0)
                            <div class="hidden sm:flex sm:items-center sm:ms-6 relative">
                                <x-dropdown align="left"
                                            width="48">
                                    <x-slot name="trigger">
                                        <button
                                                class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                            <div>Kelola Jasa</div>
                                            <div class="ms-1"><svg class="fill-current h-4 w-4"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd" />
                                                </svg></div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('services.create')">{{ __('Post Jasa Baru') }}</x-dropdown-link>
                                        <x-dropdown-link :href="route('services.index')">{{ __('Jasa Saya') }}</x-dropdown-link>
                                        <x-dropdown-link :href="route('services.highlight')">{{ __('Highlight Service') }}</x-dropdown-link>
                                        <x-dropdown-link :href="route('bank-accounts.index')">{{ __('Rekening Bank') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @elseif(auth()->user()->role === 'seller')
                            <x-nav-link :href="route('services.create')"
                                        :active="request()->routeIs('services.create')">
                                {{ __('Post Jasa') }}
                            </x-nav-link>
                        @endif

                        <!-- Customer Application Links -->
                        @if (auth()->user()->role === 'customer')
                            @php
                                $hasPendingApplication = \App\Models\ProviderApplication::where('user_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->exists();
                            @endphp
                            @if ($hasPendingApplication)
                                <x-nav-link :href="route('provider.applications')"
                                            :active="request()->routeIs('provider.applications.show')">
                                    {{ __('Pengajuan Saya') }}
                                </x-nav-link>
                            @else
                                <x-nav-link :href="route('service.apply')"
                                            :active="request()->routeIs('service.apply')">
                                    {{ __('Jadi Penyedia Jasa') }}
                                </x-nav-link>
                            @endif
                        @endif

                        <!-- Admin Links -->
                        @can('admin')
                            <div class="hidden sm:flex sm:items-center sm:ms-6 relative">
                                <x-dropdown align="left"
                                            width="48">
                                    <x-slot name="trigger">
                                        <button
                                                class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                            <div>Panel Admin</div>
                                            <div class="ms-1"><svg class="fill-current h-4 w-4"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd" />
                                                </svg></div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('admin.provider.applications')">{{ __('Semua Pengajuan') }}</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.services.index')">{{ __('Kelola Semua Jasa') }}</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endcan
                    @endauth
                </div>
            </div>

            <!-- Right Side Of Navbar -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- Notifications -->
                    <div x-data="{ open: false }"
                         class="relative me-4">
                        <button @click="open = !open"
                                class="relative p-2 rounded-full hover:bg-gray-200 focus:outline-none focus:bg-gray-200 transition">
                            <svg class="w-6 h-6 text-gray-600"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <span
                                      class="absolute top-0 right-0 block h-2 w-2 transform translate-x-1/2 -translate-y-1/2 rounded-full bg-red-500 ring-2 ring-white"></span>
                            @endif
                        </button>
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="p-3 font-semibold text-gray-800 border-b flex justify-between items-center">
                                <span>Notifikasi</span>
                                <form action="{{ route('notifications.read') }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs text-primary hover:underline">Tandai semua dibaca</button>
                                </form>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notif)
                                    <a href="{{ $notif->data['url'] ?? '#' }}"
                                       class="block px-4 py-3 hover:bg-gray-100 transition-colors">
                                        <div class="font-semibold text-sm text-gray-800">
                                            {{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                                        <div class="text-sm text-gray-600">{{ $notif->data['message'] ?? '' }}</div>
                                    </a>
                                @empty
                                    <div class="p-4 text-sm text-center text-gray-500">Tidak ada notifikasi baru.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <x-dropdown align="right"
                                width="48">
                        <x-slot name="trigger">
                            <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ optional(Auth::user())->full_name ?? 'Guest' }}</div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd" />
                                    </svg></div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profil') }}</x-dropdown-link>
                            <form method="POST"
                                  action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Keluar') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6"
                         stroke="currentColor"
                         fill="none"
                         viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }"
         class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')"
                                   :active="request()->routeIs('dashboard')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            @auth
                <!-- Customer & Seller Common Links -->
                @if (in_array(auth()->user()->role, ['customer', 'seller']))
                    <x-responsive-nav-link :href="route('orders.index')"
                                           :active="request()->routeIs('orders.*')">
                        {{ __('Pesanan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('conversations.index')"
                                           :active="request()->routeIs('conversations.*')">
                        {{ __('Chat') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('services.favorites')"
                                           :active="request()->routeIs('services.favorites')">
                        {{ __('Favorit Saya') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Seller Links -->
                @if (auth()->user()->role === 'seller')
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <div class="px-4 text-xs text-gray-500 uppercase font-semibold">Kelola Jasa</div>
                        <x-responsive-nav-link :href="route('services.create')">{{ __('Post Jasa Baru') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('services.index')">{{ __('Jasa Saya') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('services.highlight')">{{ __('Highlight Service') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('bank-accounts.index')">{{ __('Rekening Bank') }}</x-responsive-nav-link>
                    </div>
                @endif

                <!-- Customer Application Links -->
                @if (auth()->user()->role === 'customer')
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        @php
                            $hasPendingApplication = \App\Models\ProviderApplication::where('user_id', auth()->id())
                                ->where('status', 'pending')
                                ->exists();
                        @endphp
                        @if ($hasPendingApplication)
                            <x-responsive-nav-link :href="route('provider.applications')">{{ __('Pengajuan Saya') }}</x-responsive-nav-link>
                        @else
                            <x-responsive-nav-link
                                                   :href="route('service.apply')">{{ __('Jadi Penyedia Jasa') }}</x-responsive-nav-link>
                        @endif
                    </div>
                @endif

                <!-- Admin Links -->
                @can('admin')
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <div class="px-4 text-xs text-gray-500 uppercase font-semibold">Panel Admin</div>
                        <x-responsive-nav-link :href="route('admin.provider.applications')">{{ __('Semua Pengajuan') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.services.index')">{{ __('Kelola Semua Jasa') }}</x-responsive-nav-link>
                    </div>
                @endcan
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ optional(Auth::user())->full_name ?? 'Guest' }}
                </div>
                <div class="font-medium text-sm text-gray-500">{{ optional(Auth::user())->email ?? '' }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profil') }}</x-responsive-nav-link>
                <form method="POST"
                      action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
