<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">{{ $service->title }}</h1>
        <p><strong>Harga:</strong> Rp <span id="servicePrice">{{ number_format($service->price, 0, ',', '.') }}</span>
        </p>

        @if ($service->images)
            <h2 class="text-xl mt-4">Gambar</h2>
            <div class="flex space-x-2 mt-2">
                @foreach (json_decode($service->images) as $img)
                    <img src="{{ asset('storage/' . $img) }}"
                         class="w-32 h-32 object-cover border" />
                @endforeach
            </div>
        @endif

        <!-- Chat Button -->
        <a href="{{ route('conversations.start') }}"
           onclick="event.preventDefault(); document.getElementById('start-chat-{{ $service->id }}').submit();"
           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-4 inline-block">
            Chat
        </a>

        <form id="start-chat-{{ $service->id }}"
              action="{{ route('conversations.start') }}"
              method="POST"
              class="hidden">
            @csrf
            <input type="hidden"
                   name="seller_id"
                   value="{{ $service->user->id }}" />
            <input type="hidden"
                   name="product_id"
                   value="{{ $service->id }}" />
        </form>

        <!-- Order & Payment Simulation -->
        <h2 class="text-xl mt-6">Pesan & Bayar</h2>
        <form action="{{ route('orders.store') }}"
              method="POST"
              class="mt-2 p-4 border rounded">
            @csrf
            <input type="hidden"
                   name="service_id"
                   value="{{ $service->id }}" />

            <label class="block mt-2">
                Nomor Telepon:
                <input type="text"
                       name="customer_phone"
                       class="border rounded px-2 py-1 w-full"
                       placeholder="Masukkan nomor telepon Anda">
                @error('customer_phone')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </label>

            <label class="block mt-2">
                Alamat Pengiriman / Lokasi:
                <textarea name="customer_address"
                          class="border rounded px-2 py-1 w-full"
                          placeholder="Masukkan alamat Anda"></textarea>
                @error('customer_address')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </label>

            <label class="block mt-2">
                Catatan / Instruksi:
                <textarea name="note"
                          class="border rounded px-2 py-1 w-full"
                          placeholder="Misal: jam layanan, instruksi khusus"
                          rows="3"></textarea>
                @error('note')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </label>

            <input type="hidden"
                   name="payment_method"
                   value="dummy_gateway">
            <div class="mt-4 p-2 bg-gray-100 rounded">
                <p>Harga Layanan: Rp <span
                          id="servicePriceDisplay">{{ number_format($service->price, 0, ',', '.') }}</span></p>
                <p>Fee Platform (5%): Rp <span id="platformFee">0</span></p>
                <p><strong>Total Bayar: Rp <span id="totalPrice">0</span></strong></p>
            </div> <!-- Breakdown Harga -->


            <div id="paymentInfo"
                 class="mt-2 p-2 bg-gray-100 rounded">
                Simulasi gateway online. Klik 'Bayar & Pesan' untuk checkout dummy.
            </div>

            <button type="submit"
                    class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                Bayar & Pesan
            </button>
        </form>


        <div class="mt-4">
            <a href="{{ route('services.index') }}"
               class="text-blue-500 hover:underline">
                ← Kembali ke daftar
            </a>
        </div>
    </div>

    <script>
        const price = parseInt(@json($service->price), 10); // pastikan integer
        const feeRate = 0.05;
        const platformFeeEl = document.getElementById('platformFee');
        const totalPriceEl = document.getElementById('totalPrice');

        function calculateTotal() {
            const fee = Math.round(price * feeRate); // bulatkan
            const total = price + fee;

            platformFeeEl.textContent = fee.toLocaleString('id-ID');
            totalPriceEl.textContent = total.toLocaleString('id-ID');
        }



        // jalankan saat halaman load
        calculateTotal();
    </script>
</x-app-layout>
