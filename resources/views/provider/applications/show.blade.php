<x-app-layout>
    {{-- Container disesuaikan menjadi max-w-2xl --}}
    <div class="container mx-auto py-6 max-w-2xl">
        {{-- Header dan Tombol Kembali --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Detail Pengajuan</h1>
            <a href="{{ route('provider.applications') }}" class="text-blue-600 hover:underline font-semibold flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        {{-- Konten utama digabung dalam satu card --}}
        <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm">

            {{-- START: INDIKATOR TAHAP (STEPPER) --}}
            @php
                // Logika: Tahap 1 & 2 selesai, Tahap 3 aktif.
                $currentStep = 3;

                // Helper class (disalin dari desain formulir)
                $stepClasses = 'flex-1 border-t-2 pt-2 text-center text-sm font-medium';
                $activeClasses = 'border-blue-600 text-blue-600';
                $completedClasses = 'border-green-500 text-green-600';
                // $defaultClasses = 'border-gray-200 text-gray-500'; // Tidak digunakan di sini
            @endphp

            <div class="mb-8 flex items-center justify-between space-x-2 lg:space-x-8" id="stepper-indicator">
                {{-- Tahap 1: Baca Persyaratan (Selesai) --}}
                <div id="step-1-indicator" class="{{ $stepClasses }} {{ $completedClasses }}">
                    <span class="block">1. Baca Persyaratan</span>
                </div>

                {{-- Tahap 2: Isi Formulir (Selesai) --}}
                <div id="step-2-indicator" class="{{ $stepClasses }} {{ $completedClasses }}">
                    <span class="block">2. Isi Formulir</span>
                </div>

                {{-- Tahap 3: Menunggu Persetujuan (Aktif) --}}
                <div id="step-3-indicator" class="{{ $stepClasses }} {{ $activeClasses }}">
                    <span class="block">3. Menunggu Persetujuan</span>
                </div>
            </div>
            {{-- END: INDIKATOR TAHAP --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
                {{-- Bagian Informasi Dasar --}}
                <div class="md:col-span-1">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Informasi Pengaju</h2>
                    <dl class="space-y-4 text-gray-700 text-base">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-semibold text-gray-900">Nama Lengkap</dt>
                            <dd>{{ $application->user->full_name }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-semibold text-gray-900">Nomor HP</dt>
                            <dd>{{ $application->phone_number }}</dd>
                        </div>
                        <div class="border-b border-gray-100 pb-2">
                            <dt class="font-semibold text-gray-900 mb-1">Alamat</dt>
                            <dd class="text-sm">{{ $application->address }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-semibold text-gray-900">Skill Utama</dt>
                            <dd>{{ $application->skills }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-semibold text-gray-900">Pengalaman</dt>
                            <dd>{{ $application->experience }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="font-semibold text-gray-900">Pendidikan Terakhir</dt>
                            <dd>{{ $application->education }}</dd>
                        </div>
                        @if($application->portfolio)
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                <dt class="font-semibold text-gray-900">Link Portofolio</dt>
                                <dd><a href="{{ $application->portfolio }}" target="_blank" class="text-blue-500 hover:underline text-sm truncate max-w-[150px] inline-block">{{ $application->portfolio }}</a></dd>
                            </div>
                        @endif
                        <div class="flex justify-between items-center pt-2">
                            <dt class="font-semibold text-gray-900">Status Pengajuan</dt>
                            <dd>
                                @php
                                    $statusColor = [
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                    ][$application->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </dd>
                        </div>
                        @if($application->admin_notes)
                        <div class="bg-gray-100 p-3 rounded-lg mt-4">
                            <dt class="font-semibold text-gray-900 mb-1 text-sm">Catatan Admin</dt>
                            <dd class="text-xs text-gray-700">{{ $application->admin_notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- Bagian Dokumen Pendukung --}}
                <div class="md:col-span-1 md:border-l border-gray-200 md:pl-8 pt-6 md:pt-0 border-t md:border-t-0">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Dokumen Pendukung</h2>
                    <div class="space-y-6">
                        @if($application->id_card)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2 text-sm">KTP / Identitas</p>
                                <a href="{{ asset('storage/' . $application->id_card) }}" target="_blank" class="block rounded-lg overflow-hidden transition-all duration-300 transform hover:scale-[1.03]">
                                    <img src="{{ asset('storage/' . $application->id_card) }}" alt="KTP" class="w-full h-32 object-cover object-center border border-gray-200">
                                </a>
                            </div>
                        @endif

                        @if($application->selfie)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2 text-sm">Selfie dengan KTP</p>
                                <a href="{{ asset('storage/' . $application->selfie) }}" target="_blank" class="block rounded-lg overflow-hidden transition-all duration-300 transform hover:scale-[1.03]">
                                    <img src="{{ asset('storage/' . $application->selfie) }}" alt="Selfie" class="w-full h-32 object-cover object-center border border-gray-200">
                                </a>
                            </div>
                        @endif

                        @if($application->cv)
                            <div>
                                <p class="font-semibold text-gray-900 mb-2 text-sm">CV</p>
                                <a href="{{ asset('storage/' . $application->cv) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 bg-gray-100 rounded-lg text-gray-700 hover:bg-gray-200 transition-colors text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-500">
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
