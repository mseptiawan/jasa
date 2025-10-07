<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl border border-gray-200 p-6 md:p-8">
                <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-4">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                        Detail Pengajuan
                    </h1>
                    @php
                        $status = strtolower($application->status);
                        $statusStyles = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                        ];
                        $style = $statusStyles[$status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold capitalize {{ $style }}">
                        {{ $application->status }}
                    </span>
                </div>

                {{-- INFORMASI PENGGUNA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 mb-8 border-b border-gray-200 pb-8">
                    <div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $application->user->full_name }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Nomor Telepon</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $application->phone_number }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Alamat</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $application->address }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Keterampilan (Skills)</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $application->skills }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-500">Pengalaman</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $application->experience }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500">Riwayat Pendidikan</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $application->education }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-8 border-b border-gray-200 pb-8">
                    <p class="text-sm font-medium text-gray-500">Portfolio</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $application->portfolio }}</p>
                </div>

                {{-- CATATAN ADMIN --}}
                <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded-md mb-8">
                    <p class="text-sm font-medium text-gray-500">Catatan Admin</p>
                    <p class="mt-1 text-gray-800 font-normal">
                        {{ $application->admin_notes ?? 'Tidak ada catatan.' }}
                    </p>
                </div>

                {{-- FILE UPLOAD --}}
                <h2 class="text-xl font-bold mb-4 text-gray-900">Dokumen Pendukung</h2>
                <div class="flex flex-wrap gap-4 mb-8 border-b border-gray-200 pb-8">
                    @if($application->id_card)
                    <a href="{{ asset('storage/' . $application->id_card) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        KTP / Identitas
                    </a>
                    @endif
                    @if($application->selfie)
                    <a href="{{ asset('storage/' . $application->selfie) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Selfie
                    </a>
                    @endif
                    @if($application->cv)
                    <a href="{{ asset('storage/' . $application->cv) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        CV
                    </a>
                    @endif
                </div>

                {{-- FORMULIR AKSI ADMIN --}}
                @if($application->status == 'pending')
                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">Aksi</h2>

                    {{-- 1. Textarea Full Width (Dipisahkan dari tombol agar bisa full width) --}}
                    <form action="{{ route('admin.provider.applications.reject', $application->id) }}" method="POST" id="reject-form-input" class="mb-4">
                        @csrf
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Penolakan</label>
                        {{-- Textarea kini adalah w-full --}}
                        <textarea id="admin_notes" name="admin_notes" rows="3" placeholder="Masukkan alasan penolakan..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors resize-none"></textarea>
                    </form>

                    {{-- 2. Container Flex untuk Tombol-tombol (Side-by-Side, Rata Kanan) --}}
                    <div class="flex justify-end gap-3">

                        {{-- Tombol Tolak (dibungkus form sendiri, membutuhkan nilai textarea dari atas - asumsi notes optional atau dikirim via JS) --}}
                        <form action="{{ route('admin.provider.applications.reject', $application->id) }}" method="POST">
                            @csrf
                            {{-- Input tersembunyi untuk mengirim catatan penolakan jika tidak ada JS --}}
                            <input type="hidden" name="admin_notes" value="">
                            <button type="submit"
                                    class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                Tolak Pengajuan
                            </button>
                        </form>

                        {{-- Tombol Setujui --}}
                        <form action="{{ route('admin.provider.applications.approve', $application->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                                Setujui Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
