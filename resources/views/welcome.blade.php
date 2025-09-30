<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>JasaReceh - Service Finder</title>
    <link rel="icon"
          type="image/x-icon"
          href="{{ asset('logo-JasaReceh.ico') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Montserrat -->
    <link rel="preconnect"
          href="https://fonts.googleapis.com">
    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8fafc;
            /* Tailwind gray-50 */
        }

        /* Custom Colors */
        .bg-primary {
            background-color: #2b3cd7;
        }

        .text-primary {
            color: #2b3cd7;
        }

        .border-primary {
            border-color: #2b3cd7;
        }

        .bg-accent {
            background-color: #ffd231;
        }

        .text-accent {
            color: #ffd231;
        }

        /* Custom focus ring color */
        .focus\:ring-primary:focus {
            --tw-ring-color: #2b3cd7;
        }

        /* Autocomplete list styling */
        #autocomplete-results li:hover {
            background-color: #eef2ff;
            /* Tailwind indigo-50 */
        }

        /* Underline animation */
        .animated-underline {
            position: relative;
            display: inline-block;
        }

        .animated-underline::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: currentColor;
            transform-origin: right;
            transform: scaleX(0);
            transition: transform 0.3s ease-out;
        }

        .animated-underline:hover::after {
            transform-origin: left;
            transform: scaleX(1);
        }

        /* Spinner for loading indicator */
        .loader {
            border: 4px solid #f3f3f3;
            /* Light grey */
            border-top: 4px solid #2b3cd7;
            /* Blue */
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="text-gray-800">

    <!-- Notification Modal -->
    <div id="notification-modal"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-sm text-center">
            <p id="modal-message"
               class="mb-4 text-gray-700"></p>
            <button id="modal-close-btn"
                    class="bg-primary text-white font-semibold py-2 px-6 rounded-lg hover:opacity-90 transition-opacity">
                OK
            </button>
        </div>
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-sm w-full border-b border-gray-200">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center py-3 px-4 sm:px-6 lg:px-8 gap-4">
            <!-- Logo -->
            <a href="{{ url('/') }}"
               class="order-1">
                <img src="{{ asset('images/logo-JasaReceh.png') }}"
                     alt="JasaReceh"
                     class="h-10 w-auto">
            </a>
            <div class="flex items-center space-x-8 order-2 md:order-3">
                <a href="{{ route('login') }}"
                   class="animated-underline text-gray-700 hover:text-primary font-semibold transition-colors">Login</a>
                <a href="{{ route('register') }}"
                   class="animated-underline text-accent hover:text-yellow-400 font-semibold transition-colors">Register</a>
            </div>
            <!-- Search Form -->
            <div class="w-full md:w-auto md:flex-1 md:max-w-md lg:max-w-lg order-3 md:order-2">


                <!-- Auth Links -->

            </div>
    </header>

    <!-- Main Container -->
    <div class="container mx-auto p-4 md:p-8 max-w-7xl">

        <main>
            <!-- Location & Search Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 mb-8">
                <h2 class="text-xl font-bold mb-4 text-gray-900">Temukan Layanan Terbaik di Dekat Anda</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Geolocation Search -->
                    {{-- <div class="flex flex-col">
                        <p class="text-gray-600 mb-2">Dapatkan lokasi Anda secara instan.</p>
                        <button type="button"
                                id="btn-nearby"
                                class="w-full flex items-center justify-center gap-2 bg-primary text-white font-bold py-3 px-4 rounded-lg shadow-md hover:opacity-90 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="20"
                                 height="20"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12"
                                        cy="10"
                                        r="3"></circle>
                            </svg>
                            Cari Layanan Terdekat
                        </button>
                    </div> --}}
                    <form action="{{ route('home') }}"
                          method="GET"
                          class="relative">
                        <p class="text-gray-600 mb-2">Cari berdasarkan kata kunci.</p>
                        <div class="search-container flex gap-2">
                            <input type="text"
                                   name="search"
                                   placeholder="Cari layanan apa pun..."
                                   value="{{ request('search') }}"
                                   class="w-full py-2 pl-4 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                   autocomplete="off">
                            <button type="submit"
                                    class="bg-primary text-white font-bold p-3 rounded-lg hover:opacity-90 transition-opacity flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>

                        </div>
                    </form>
                    <!-- Address Form -->
                    <form id="location-form"
                          action="{{ route('services.nearby') }}"
                          method="get"
                          class="flex flex-col w-full">
                        <p class="text-gray-600 mb-2">Atau ketik alamat Anda secara manual.</p>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-grow">
                                <input type="text"
                                       id="address-input"
                                       placeholder="Ketik alamat Anda..."
                                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                       autocomplete="off"
                                       required>
                                <input type="hidden"
                                       name="lat"
                                       id="lat">
                                <input type="hidden"
                                       name="lng"
                                       id="lng">
                                <ul id="autocomplete-results"
                                    class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-lg max-h-60 overflow-y-auto z-10 list-none p-0 m-0 hidden">
                                </ul>
                            </div>
                            <button type="submit"
                                    class="bg-primary text-white font-bold p-3 rounded-lg hover:opacity-90 transition-opacity">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Highlighted Services Section -->
            @php
                $highlightServices = $services->filter(
                    fn($s) => $s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until),
                );
            @endphp

            @if ($highlightServices->count() > 0)
                <section class="mb-12">
                    <h2 class="text-2xl font-bold mb-5 text-gray-900">Layanan Unggulan</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($highlightServices as $service)
                            @php
                                $images = json_decode($service->images, true);
                                $mainImage = $images[0] ?? null;
                                $profilePhoto = $service->user->profile_photo ?? null;
                            @endphp
                            <div
                                 class="bg-white rounded-lg overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 border-2 border-accent">
                                <div class="relative">
                                    <a href="{{ route('services.show', $service->slug) }}">
                                        @if ($mainImage)
                                            <img src="{{ asset('storage/' . $mainImage) }}"
                                                 alt="{{ $service->title }}"
                                                 class="w-full h-40 object-cover">
                                        @else
                                            <div
                                                 class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                                                Tidak Ada Gambar</div>
                                        @endif
                                    </a>
                                    <span
                                          class="absolute top-2 left-2 bg-accent text-gray-900 text-xs font-bold px-3 py-1 rounded-full">UNGGULAN</span>
                                </div>
                                <div class="p-4">
                                    <a href="{{ route('services.show', $service->slug) }}"
                                       class="block font-bold text-lg mb-2 text-gray-900 hover:text-primary truncate transition-colors">{{ $service->title }}</a>
                                    <p class="text-lg font-semibold text-green-600 mb-3">Rp
                                        {{ number_format($service->price, 0, ',', '.') }}</p>
                                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                        @if ($profilePhoto)
                                            <img src="{{ asset('storage/' . $profilePhoto) }}"
                                                 alt="{{ $service->user->full_name }}"
                                                 class="w-7 h-7 rounded-full object-cover">
                                        @else
                                            <div class="w-7 h-7 rounded-full bg-gray-300"></div>
                                        @endif
                                        <span>{{ $service->user->full_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                 fill="currentColor"
                                                 viewBox="0 0 20 20">
                                                <path
                                                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- All Services Section -->
            @php
                $normalServices = $services->filter(
                    fn($s) => !($s->is_highlight && $s->highlight_until && now()->lte($s->highlight_until)),
                );
            @endphp
            <section>
                <h2 class="text-2xl font-bold mb-5 text-gray-900">Semua Layanan</h2>
                <div id="normal-services-grid"
                     class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($normalServices as $service)
                        @php
                            $images = json_decode($service->images, true);
                            $mainImage = $images[0] ?? null;
                            $profilePhoto = $service->user->profile_photo ?? null;
                        @endphp
                        <div
                             class="normal-service-card bg-white rounded-lg border border-gray-200 overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
                            <a href="{{ route('services.show', $service->slug) }}">
                                @if ($mainImage)
                                    <img src="{{ asset('storage/' . $mainImage) }}"
                                         alt="{{ $service->title }}"
                                         class="w-full h-40 object-cover">
                                @else
                                    <div
                                         class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                                        Tidak Ada Gambar</div>
                                @endif
                            </a>
                            <div class="p-4">
                                <a href="{{ route('services.show', $service->slug) }}"
                                   class="block font-bold text-lg mb-2 text-gray-900 hover:text-primary truncate transition-colors">{{ $service->title }}</a>
                                <p class="text-lg font-semibold text-green-600 mb-3">Rp
                                    {{ number_format($service->price, 0, ',', '.') }}</p>
                                <p class="text-gray-500 mb-4">
                                    {{ Str::words(strip_tags($service->description), 15, '...') }}
                                </p>
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                    @if ($profilePhoto)
                                        <img src="{{ asset('storage/' . $profilePhoto) }}"
                                             alt="{{ $service->user->full_name }}"
                                             class="w-7 h-7 rounded-full object-cover">
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-gray-300"></div>
                                    @endif
                                    <span>{{ $service->user->full_name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($service->avg_rating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                             fill="currentColor"
                                             viewBox="0 0 20 20">
                                            <path
                                                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500 py-10">Tidak ada layanan yang tersedia saat
                            ini.</p>
                    @endforelse
                </div>

                <!-- Loading Indicator -->
                <div id="loading-indicator"
                     class="w-full flex justify-center items-center py-8 gap-3 text-gray-600 hidden">
                    <div class="loader"></div>
                    <span>Memuat layanan lainnya...</span>
                </div>
            </section>
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Existing JS for Modal, Geolocation and Autocomplete ---
            const addressInput = document.getElementById('address-input');
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');
            const resultsList = document.getElementById('autocomplete-results');
            const form = document.getElementById('location-form');
            const modal = document.getElementById('notification-modal');
            const modalMessage = document.getElementById('modal-message');
            const modalCloseBtn = document.getElementById('modal-close-btn');
            let geocodeTimeout = null;

            function showModal(message) {
                modalMessage.textContent = message;
                modal.classList.remove('hidden');
            }

            function hideModal() {
                modal.classList.add('hidden');
            }

            modalCloseBtn.addEventListener('click', hideModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    hideModal();
                }
            });

            addressInput.addEventListener('input', () => {
                clearTimeout(geocodeTimeout);
                const query = addressInput.value.trim();
                if (query.length < 3) {
                    resultsList.style.display = 'none';
                    return;
                }
                geocodeTimeout = setTimeout(() => {
                    fetch(
                            `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1`
                        )
                        .then(res => res.json())
                        .then(data => {
                            resultsList.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const li = document.createElement('li');
                                    li.textContent = item.display_name;
                                    li.className = 'p-3 cursor-pointer';
                                    li.addEventListener('click', () => {
                                        addressInput.value = item.display_name;
                                        latInput.value = item.lat;
                                        lngInput.value = item.lon;
                                        resultsList.style.display = 'none';
                                    });
                                    resultsList.appendChild(li);
                                });
                                resultsList.style.display = 'block';
                            } else {
                                resultsList.style.display = 'none';
                            }
                        })
                        .catch(() => {
                            showModal(
                                'Tidak dapat mengambil saran alamat. Silakan periksa koneksi Anda.'
                            );
                        });
                }, 500);
            });

            document.addEventListener('click', (e) => {
                if (!addressInput.contains(e.target) && !resultsList.contains(e.target)) {
                    resultsList.style.display = 'none';
                }
            });

            form.addEventListener('submit', (e) => {
                if (!latInput.value || !lngInput.value) {
                    e.preventDefault();
                    showModal(
                        'Silakan pilih alamat yang valid dari daftar dropdown untuk mengisi koordinat.');
                }
            });

            document.getElementById('btn-nearby').addEventListener('click', () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        latInput.value = position.coords.latitude;
                        lngInput.value = position.coords.longitude;
                        fetch(
                                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latInput.value}&lon=${lngInput.value}`
                            )
                            .then(res => res.json())
                            .then(data => {
                                addressInput.value = data.display_name || 'Lokasi Saat Ini';
                                form.submit();
                            })
                            .catch(() => {
                                form.submit();
                            })
                    }, () => {
                        showModal(
                            'Tidak dapat mengambil lokasi Anda. Silakan periksa izin browser Anda.'
                        );
                    });
                } else {
                    showModal('Geolocation tidak didukung oleh browser Anda.');
                }
            });

            // --- New Infinite Scroll Logic ---
            const normalServiceCards = document.querySelectorAll('.normal-service-card');
            const loadingIndicator = document.getElementById('loading-indicator');

            let currentlyDisplayed = 8; // The number of cards to show initially
            const cardsPerLoad = 4; // The number of cards to load each time you scroll
            let isLoading = false;

            // Initially hide cards that are beyond the initial limit
            normalServiceCards.forEach((card, index) => {
                if (index >= currentlyDisplayed) {
                    card.classList.add('hidden');
                }
            });

            // If all cards are already displayed, no need for the loader
            if (normalServiceCards.length <= currentlyDisplayed) {
                loadingIndicator.remove();
            }

            const loadMoreCards = () => {
                isLoading = true;
                loadingIndicator.classList.remove('hidden');

                // Simulate a network delay
                setTimeout(() => {
                    const nextLimit = currentlyDisplayed + cardsPerLoad;

                    for (let i = currentlyDisplayed; i < nextLimit; i++) {
                        if (normalServiceCards[i]) {
                            normalServiceCards[i].classList.remove('hidden');
                        }
                    }

                    currentlyDisplayed = nextLimit;
                    loadingIndicator.classList.add('hidden');
                    isLoading = false;

                    // If all cards are now shown, remove the loading indicator and the scroll listener
                    if (currentlyDisplayed >= normalServiceCards.length) {
                        loadingIndicator.remove();
                        window.removeEventListener('scroll', handleScroll);
                    }

                }, 1000); // 1 second delay
            };

            const handleScroll = () => {
                // Check if the user is near the bottom of the page and not currently loading
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200 && !isLoading) {
                    loadMoreCards();
                }
            };

            if (normalServiceCards.length > currentlyDisplayed) {
                window.addEventListener('scroll', handleScroll);
            }

        });
    </script>

</html>
