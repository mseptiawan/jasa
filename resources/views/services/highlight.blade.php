<x-app-layout>
    {{-- CSS Kustom & Font Montserrat --}}
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
        }

        .text-primary {
            color: #2b3cd7;
        }

        .bg-primary {
            background-color: #2b3cd7;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-color: #a7f3d0;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #fca5a5;
        }

        /* Table styling */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        th,
        td {
            padding: 0.75rem 1rem;
        }
    </style>

    {{-- Container utama --}}
    <div class="container mx-auto py-8 px-4 md:px-8 max-w-4xl mt-16">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-gray-200">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Highlight Layanan</h1>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($services->isEmpty())
                <div class="text-center py-10 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mx-auto h-16 w-16 text-gray-400"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 text-xl font-semibold">Belum ada layanan untuk di-highlight.</p>
                    <p class="mt-2">Silakan tambahkan layanan terlebih dahulu.</p>
                    <a href="{{ route('services.create') }}"
                       class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700">
                        Buat Layanan Baru
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Jasa
                                </th>
                                <th class="text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                    Status Highlight</th>
                                <th class="text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Sisa
                                    Waktu</th>
                                <th class="text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Fee
                                </th>
                                <th class="text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($services as $service)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="text-sm text-gray-900">{{ $service->title }}</td>
                                    <td class="text-sm text-gray-900">
                                        @if ($service->is_highlight && $service->highlight_until > now())
                                            <span
                                                  class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Aktif</span>
                                        @else
                                            <span
                                                  class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tidak
                                                Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-sm text-gray-600">
                                        @if ($service->highlight_until && $service->highlight_until > now())
                                            <span class="countdown"
                                                  data-until="{{ $service->highlight_until }}"></span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-sm text-gray-900">
                                        Rp {{ number_format($service->highlight_fee, 0, ',', '.') }}
                                    </td>
                                    <td class="text-sm font-medium">
                                        <a href="{{ route('services.highlight.showPay', $service->slug) }}"
                                           class="text-primary hover:text-blue-700 flex items-center gap-1">
                                            Bayar Highlight
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Countdown Script --}}
    <script>
        function updateCountdowns() {
            const elements = document.querySelectorAll('.countdown');
            const now = new Date().getTime();

            elements.forEach(el => {
                const until = new Date(el.dataset.until).getTime();
                const distance = until - now;

                if (distance <= 0) {
                    el.innerHTML = 'Waktu habis';
                } else {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    el.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                }
            });
        }

        // Update setiap 1 detik
        setInterval(updateCountdowns, 1000);
        // Jalankan sekali di load halaman
        updateCountdowns();
    </script>
</x-app-layout>
