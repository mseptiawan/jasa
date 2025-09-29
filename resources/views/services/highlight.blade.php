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

        /* Styling untuk pesan notifikasi */
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
    </style>

    {{-- Container utama dengan margin atas untuk menghindari navbar --}}
    <div class="container mx-auto py-8 px-4 md:px-8 max-w-4xl mt-16">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-gray-200">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Highlight Layanan</h1>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            @if($services->isEmpty())
            <div class="text-center py-10 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-4 text-xl font-semibold">Belum ada layanan untuk di-highlight.</p>
                <p class="mt-2">Silakan tambahkan layanan terlebih dahulu.</p>
                <a href="{{ route('services.create') }}" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Buat Layanan Baru
                </a>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Jasa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Status Highlight</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Fee</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($services as $service)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($service->is_highlight && $service->highlight_until > now())
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Aktif
                                </span>
                                @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Tidak Aktif
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $service->highlight_until ? $service->highlight_until->diffForHumans() : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($service->highlight_fee, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('services.highlight.showPay', $service->slug) }}"
                                   class="text-primary hover:text-blue-700 transition-colors duration-200 font-medium flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zM8 12l2 2 4-4" />
                                    </svg>
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
</x-app-layout>
