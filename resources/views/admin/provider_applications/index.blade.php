<x-app-layout>
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-6">
            Pusat Pengajuan Layanan
        </h1>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Pengajuan Penyedia Jasa</h2>

            <div class="divide-y divide-gray-200">
                @forelse($applications as $app)
                    <div class="bg-gray-50 rounded-lg p-5 flex flex-col md:flex-row md:items-center md:justify-between transition-colors duration-200 hover:bg-gray-100">
                        <div class="flex-grow mb-4 md:mb-0">
                            <p class="text-sm text-gray-500 font-medium">Tanggal Pengajuan</p>
                            <p class="text-base font-medium text-gray-800">{{ $app->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <div class="flex-grow mb-4 md:mb-0">
                            <p class="text-sm text-gray-500 font-medium">Nama Penyedia</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $app->user->full_name }}</p>
                        </div>

                        <div class="flex-grow mb-4 md:mb-0 text-center">
                            <p class="text-sm text-gray-500 font-medium md:hidden">Status</p>
                            @php
                                $status = strtolower($app->status);
                                $statusStyles = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                                $style = $statusStyles[$status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize {{ $style }}">
                                {{ $app->status }}
                            </span>
                        </div>

                        <div class="md:text-right">
                            <a href="{{ route('admin.provider.applications.show', $app->slug) }}"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Lihat Detail
                                <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500 font-medium">
                        Tidak ada pengajuan yang tersedia saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
