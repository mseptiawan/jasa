<nav x-data="{ open: false }"
     class="sticky top-0 z-40 bg-white/90 backdrop-blur-sm w-full border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo-JasaReceh.png') }}"
                             alt="JasaReceh"
                             class="h-10 w-auto">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    {{-- Beranda: Diubah menjadi <a> dengan efek gradasi Alpine.js --}}
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out relative group overflow-hidden"
                       :class="request()->routeIs('dashboard') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300'"
                       x-data="{
                            x: 0,
                            y: 0,
                            show: false,
                            updatePos(event) {
                                const rect = event.target.getBoundingClientRect();
                                this.x = event.clientX - rect.left;
                                this.y = event.clientY - rect.top;
                                this.show = true;
                            }
                        }"
                       @mouseenter="show = true"
                       @mouseleave="show = false"
                       @mousemove="updatePos($event)"
                       title="{{ __('Beranda') }}">

                        {{-- Elemen Gradasi yang Mengikuti Kursor --}}
                        <span x-show="show"
                              x-cloak
                              class="absolute inset-0 bg-gradient-radial from-indigo-500/20 to-transparent pointer-events-none transition-opacity duration-300"
                              :style="{
                                  left: x + 'px',
                                  top: y + 'px',
                                  transform: 'translate(-50%, -50%)',
                                  opacity: show ? 1 : 0,
                                  width: '150px',
                                  height: '150px'
                              }">
                        </span>

                        <span class="relative z-10">{{ __('Beranda') }}</span>
                    </a>

                    @auth
                        @if (in_array(auth()->user()->role, ['customer', 'seller']))
                            {{-- Pesanan tetap TEXT --}}
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
                            {{-- Favorit Saya dan Keranjang dipindahkan ke sisi kanan navbar --}}
                        @endif

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
                                {{-- PERUBAHAN: Menambahkan Alpine.js untuk pop-up hover. Latar belakang diubah ke PUTIH SOLID (bg-white) --}}
                                <div x-data="{ open: false }"
                                     @mouseenter="open = true"
                                     @mouseleave="open = false"
                                     class="relative hidden sm:flex sm:items-center sm:ms-6">
                                    <x-nav-link :href="route('service.apply')"
                                                :active="request()->routeIs('service.apply')"
                                                class="z-10">
                                        {{ __('Jadi Penyedia Jasa') }}
                                    </x-nav-link>
                                    {{-- Pop-up Hover dengan latar belakang PUTIH SOLID. Menghilangkan backdrop-blur-sm --}}
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-1"
                                         class="absolute z-20 w-80 top-full mt-2 start-0 bg-white border border-gray-200 rounded-lg shadow-xl p-4">
                                        <p class="font-bold mb-2 text-sm text-gray-800">Langkah Jadi Penyedia Jasa:</p>
                                        <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                                            <li>Lengkapi Profil Anda.</li>
                                            <li>Ajukan pendaftaran sebagai penyedia jasa.</li>
                                            <li>Tunggu konfirmasi dari Admin.</li>
                                            <li>Mulai posting jasa Anda!</li>
                                        </ol>
                                        {{-- Arrow disetel ke left-4 agar mengarah tepat ke tautan. Border disetel ke putih solid (border-b-white) --}}
                                        <div class="absolute top-0 left-4 transform -translate-y-full w-0 h-0 border-l-8 border-l-transparent border-r-8 border-r-transparent border-b-8 border-b-white"></div>
                                    </div>
                                </div>
                            @endif
                        @endif

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

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    @if (in_array(auth()->user()->role, ['customer', 'seller']))
                        <a href="{{ route('cart.index') }}"
                           class="relative p-2 rounded-full hover:bg-gray-200 focus:outline-none focus:bg-gray-200 transition me-4 {{ request()->routeIs('cart.index') ? 'bg-gray-100 text-gray-700' : '' }}"
                           title="{{ __('Keranjang') }}">
                            <svg class="w-6 h-6 text-gray-600"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @php
                                $cartCount = session('cart') ? count(session('cart')) : 0;
                            @endphp
                            @if ($cartCount > 0)
                                <span
                                    class="absolute top-0 right-0 block h-4 w-4 transform translate-x-1/2 -translate-y-1/2 rounded-full bg-red-500 ring-2 ring-white text-xs text-white flex items-center justify-center font-bold">
                                    {{ $cartCount > 9 ? '9+' : $cartCount }}
                                </span>
                            @endif
                        </a>

                        {{-- Favorit Saya Icon --}}
                        <a href="{{ route('services.favorites') }}"
                           class="relative p-2 rounded-full hover:bg-gray-200 focus:outline-none focus:bg-gray-200 transition me-4 {{ request()->routeIs('services.favorites') ? 'bg-gray-100 text-gray-700' : '' }}"
                           title="{{ __('Favorit Saya') }}">
                            <svg class="w-6 h-6 text-gray-600"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </a>
                    @endif

                    <div x-data="{ open: false }"
                         class="relative me-4">
                        <button @click="open = !open"
                                class="relative p-2 rounded-full hover:bg-gray-200 focus:outline-none focus:bg-gray-200 transition">
                            {{-- PERUBAHAN: Menambahkan class kondisional untuk warna "emas" (yellow-600) --}}
                            <svg class="w-6 h-6 transition duration-150 ease-in-out"
                                 :class="{ 'text-yellow-600': open, 'text-gray-600': !open }"
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
                             {{-- Lebar Pop-up diubah dari w-80 menjadi w-96 --}}
                             class="absolute right-0 mt-2 w-96 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
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
                                    {{-- Bagian Notifikasi Kosong dengan Sticker --}}
                                    <div class="p-6 text-center flex flex-col items-center justify-center">
                                        <img src="{{ asset('images/image.png') }}"
                                             alt="Tidak ada notifikasi"
                                             class="w-32 h-32 mb-4 object-contain">
                                        <div class="text-lg font-semibold text-gray-700">Semua notifikasi sudah dibaca!</div>
                                        <div class="text-sm text-gray-500 mt-1">Anda sudah *up-to-date*.</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

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

    <div :class="{ 'block': open, 'hidden': !open }"
         class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')"
                                   :active="request()->routeIs('dashboard')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            @auth
                @if (in_array(auth()->user()->role, ['customer', 'seller']))
                    {{-- Pesanan tetap TEXT --}}
                    <x-responsive-nav-link :href="route('orders.index')"
                                           :active="request()->routeIs('orders.*')">
                        {{ __('Pesanan') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('conversations.index')"
                                           :active="request()->routeIs('conversations.*')">
                        {{ __('Chat') }}
                    </x-responsive-nav-link>

                    {{-- Favorit Saya di sini sebagai IKON + TEXT --}}
                    <x-responsive-nav-link :href="route('services.favorites')"
                                           :active="request()->routeIs('services.favorites')"
                                           class="flex items-center">
                        <svg class="w-5 h-5 me-2"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span>{{ __('Favorit Saya') }}</span>
                    </x-responsive-nav-link>

                    {{-- Keranjang di sini sebagai IKON + TEXT --}}
                    <x-responsive-nav-link :href="route('cart.index')"
                                           :active="request()->routeIs('cart.index')"
                                           class="flex items-center">
                        <svg class="w-5 h-5 me-2"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>{{ __('Keranjang') }}</span>
                    </x-responsive-nav-link>
                @endif

                @if (auth()->user()->role === 'seller')
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <div class="px-4 text-xs text-gray-500 uppercase font-semibold">Kelola Jasa</div>
                        <x-responsive-nav-link :href="route('services.create')">{{ __('Post Jasa Baru') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('services.index')">{{ __('Jasa Saya') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('services.highlight')">{{ __('Highlight Service') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('bank-accounts.index')">{{ __('Rekening Bank') }}</x-responsive-nav-link>
                    </div>
                @endif

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
                            <x-responsive-nav-link :href="route('service.apply')">
                                {{ __('Jadi Penyedia Jasa') }}
                                {{-- Penjelasan singkat di mode responsif --}}
                                <div class="text-xs text-gray-500 mt-1 font-normal">
                                    Ajukan diri dan mulai tawarkan jasa Anda.
                                </div>
                            </x-responsive-nav-link>
                        @endif
                    </div>
                @endif

                @can('admin')
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <div class="px-4 text-xs text-gray-500 uppercase font-semibold">Panel Admin</div>
                        <x-responsive-nav-link :href="route('admin.provider.applications')">{{ __('Semua Pengajuan') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.services.index')">{{ __('Kelola Semua Jasa') }}</x-responsive-nav-link>
                    </div>
                @endcan
            @endauth
        </div>

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
