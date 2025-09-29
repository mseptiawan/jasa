<x-app-layout>
    <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8 max-w-7xl">
        {{-- Header dan Tombol Kembali --}}
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900">Detail Pengajuan</h1>
            <a href="{{ route('provider.applications') }}" class="text-primary hover:underline font-semibold flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke daftar
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-8 md:p-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                {{-- Bagian Informasi Dasar --}}
                <div class="md:col-span-2">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Informasi Pengaju</h2>
                    <dl class="space-y-6 text-gray-700 text-lg">
                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <dt class="font-semibold text-gray-900">Nama Lengkap</dt>
                            <dd>{{ $application->user->full_name }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <dt class="font-semibold text-gray-900">Nomor HP</dt>
                            <dd>{{ $application->phone_number }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <dt class="font-semibold text-gray-900">Alamat</dt>
                            <dd>{{ $application->address }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <dt class="font-semibold text-gray-900">Skill Utama</dt>
                            <dd>{{ $application->skills }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <dt class="font-semibold text-gray-900">Pengalaman</dt>
                            <dd>{{ $application->experience }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                            <dt class="font-semibold text-gray-900">Pendidikan Terakhir</dt>
                            <dd>{{ $application->education }}</dd>
                        </div>
                        @if($application->portfolio)
                            <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                <dt class="font-semibold text-gray-900">Link Portofolio</dt>
                                <dd><a href="{{ $application->portfolio }}" target="_blank" class="text-blue-500 hover:underline">{{ $application->portfolio }}</a></dd>
                            </div>
                        @endif
                        <div class="flex justify-between items-center pb-3">
                            <dt class="font-semibold text-gray-900">Status Pengajuan</dt>
                            <dd>
                                @php
                                    $statusColor = [
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                    ][$application->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </dd>
                        </div>
                        @if($application->admin_notes)
                        <div class="bg-gray-100 p-4 rounded-lg mt-6">
                            <dt class="font-semibold text-gray-900 mb-2">Catatan Admin</dt>
                            <dd class="text-sm text-gray-700">{{ $application->admin_notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- Bagian Dokumen Pendukung --}}
                <div class="md:col-span-1 border-t md:border-t-0 md:border-l border-gray-200 pt-8 md:pl-12 md:pt-0">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Dokumen Pendukung</h2>
                    <div class="space-y-8">
                        @if($application->id_card)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2">KTP / Identitas</p>
                                <a href="{{ asset('storage/' . $application->id_card) }}" target="_blank" class="block rounded-lg overflow-hidden transition-all duration-300 transform hover:scale-105">
                                    <img src="{{ asset('storage/' . $application->id_card) }}" alt="KTP" class="w-full h-40 object-cover object-center border border-gray-200">
                                </a>
                            </div>
                        @endif

                        @if($application->selfie)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2">Selfie dengan KTP</p>
                                <a href="{{ asset('storage/' . $application->selfie) }}" target="_blank" class="block rounded-lg overflow-hidden transition-all duration-300 transform hover:scale-105">
                                    <img src="{{ asset('storage/' . $application->selfie) }}" alt="Selfie" class="w-full h-40 object-cover object-center border border-gray-200">
                                </a>
                            </div>
                        @endif

                        @if($application->cv)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2">CV</p>
                                <a href="{{ asset('storage/' . $application->cv) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 bg-gray-100 rounded-lg text-gray-700 hover:bg-gray-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-500">
                                        <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                                        <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                    </svg>
                                    <span>Lihat Dokumen CV</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
