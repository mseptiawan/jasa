<nav x-data="{ open: false }"
     class="bg-gray-100 border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <!-- <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a> -->
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Beranda -->
                    @if (auth()->check())
                        <x-nav-link :href="route('dashboard')"
                                    :active="request()->routeIs('dashboard')">
                            {{ __('Beranda') }}
                        </x-nav-link>
                    @endif


                    <!-- Untuk User Biasa -->
                    @auth
                        @if (!auth()->user()->isAdmin() && auth()->user()->role !== 'seller')
                            <x-nav-link :href="route('service.apply')"
                                        :active="request()->routeIs('service.apply')">
                                {{ __('Apply Become Service Provider') }}
                            </x-nav-link>

                            @php
                                $hasPendingApplication = \App\Models\ProviderApplication::where('user_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->exists();
                            @endphp

                            @if ($hasPendingApplication)
                                <x-nav-link :href="route('provider.applications')"
                                            :active="request()->routeIs('provider.applications')">
                                    {{ __('Pengajuan Saya') }}
                                </x-nav-link>
                            @endif
                        @endif
                    @endauth

                    <!-- Untuk Admin -->
                    @auth
                        @can('admin')
                            <x-nav-link :href="route('admin.provider.applications')"
                                        :active="request()->routeIs('admin.provider.applications')">
                                {{ __('Semua Pengajuan') }}
                            </x-nav-link>
                        @endcan
                    @endauth

                    @auth
                        @if (auth()->user()->role === 'seller')
                            {{-- cuma seller --}}
                            @php
                                $application = \App\Models\ProviderApplication::where('user_id', auth()->id())->first();
                            @endphp

                            @if ($application && $application->status === 'approved')
                                <x-nav-link :href="route('services.create')"
                                            :active="request()->routeIs('services.create')">
                                    {{ __('Post Jasa') }}
                                </x-nav-link>
                            @endif
                        @endif
                    @endauth


                    <!-- Kelola Jasa untuk Admin -->
                    @auth
                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.services.index')"
                                        :active="request()->routeIs('admin.services.*')">
                                {{ __('Kelola Jasa') }}
                            </x-nav-link>
                        @endif
                    @endauth
                    <!-- Chat Menu -->
                    @auth
                        @php
                            $roles = ['customer', 'seller']; // role yang boleh liat chat
                        @endphp

                        @if (in_array(auth()->user()->role, $roles))
                            <x-nav-link :href="route('conversations.index')"
                                        :active="request()->routeIs('conversations.*')">
                                {{ __('Chat') }}
                            </x-nav-link>
                        @endif
                    @endauth

                    @auth
                        @php
                            $user = auth()->user();
                        @endphp

                        @if ($user->role !== 'admin') {{-- cuma customer & seller --}}
                            <x-nav-link :href="route('orders.index')"
                                        :active="request()->routeIs('orders.*')">
                                {{ __('Pesanan') }}

                                {{-- badge merah cuma buat seller --}}
                                @if ($user->role === 'seller')
                                    @php
                                        $pendingCount = \App\Models\Order::whereHas(
                                            'service',
                                            fn($q) => $q->where('user_id', $user->id),
                                        )
                                            ->where('status', 'pending')
                                            ->count();
                                    @endphp
                                    @if ($pendingCount > 0)
                                        <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2">
                                            {{ $pendingCount }}
                                        </span>
                                    @endif
                                @endif
                            </x-nav-link>
                        @endif
                    @endauth


                    <!-- Favorit Saya -->
                    @auth
                        @if (in_array(auth()->user()->role, ['customer', 'seller']))
                            <x-nav-link :href="route('services.favorites')"
                                        :active="request()->routeIs('services.favorites')">
                                {{ __('Favorit Saya') }}
                            </x-nav-link>
                        @endif
                    @endauth

                    <!-- Jasa Saya -->
                    @auth
                        @if (!auth()->user()->isAdmin())
                            @php
                                $servicesCount = \App\Models\Service::where('user_id', auth()->id())->count();
                            @endphp
                            @if ($servicesCount > 0)
                                <x-nav-link :href="route('services.index')"
                                            :active="request()->routeIs('services.index')">
                                    {{ __('Jasa Saya') }}
                                </x-nav-link>
                            @endif
                            {{-- Menu Highlight Service --}}
                            @if ($servicesCount > 0)
                                <x-nav-link :href="route('services.highlight')"
                                            :active="request()->routeIs('services.highlight')">
                                    {{ __('Highlight Service') }}
                                </x-nav-link>
                            @endif
                        @endif
                    @endauth
                    @if (optional(auth()->user())->role === 'seller')
                        <a href="{{ route('bank-accounts.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">Bank
                            Accounts</a>
                    @endif
                    <!-- Notifikasi Bell -->
                    @auth
                        <div x-data="{ open: false }"
                             class="relative">
                            <button @click="open = !open"
                                    class="relative focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-6 h-6 mt-7 text-gray-700 hover:text-blue-600"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.5"
                                          d="M14.857 17.081A4.001 4.001 0 0112 20a4.001 4.001 0 01-2.857-2.919M5 8a7 7 0 0114 0v4l1.5 2.5H3.5L5 12V8z" />
                                </svg>

                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-5 -right-1 bg-red-500 text-white text-xs rounded-full px-1">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded shadow-lg z-50">
                                <div class="p-2 font-semibold border-b flex justify-between items-center">
                                    <span>Notifikasi</span>
                                    <form action="{{ route('notifications.read') }}"
                                          method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="text-xs text-blue-600 hover:underline">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                </div>

                                @forelse(auth()->user()->unreadNotifications as $notif)
                                    <a href="{{ $notif->data['url'] ?? '#' }}"
                                       class="block px-4 py-2 hover:bg-gray-100">
                                        <div class="text-sm font-medium">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                                        <div class="text-xs text-gray-500">{{ $notif->data['message'] ?? '' }}</div>
                                    </a>
                                @empty
                                    <div class="p-3 text-gray-500">Belum ada notifikasi</div>
                                @endforelse
                            </div>
                        </div>
                    @endauth
                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right"
                            width="48">
                    <x-slot name="trigger">
                        <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">

                            <div>{{ optional(Auth::user())->full_name ?? 'Guest' }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
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
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ optional(Auth::user())->full_name ?? 'Guest' }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ optional(Auth::user())->email ?? 'Guest' }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST"
                      action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
