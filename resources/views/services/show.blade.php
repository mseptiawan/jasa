<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">{{ $service->title }}</h1>

        <p><strong>Deskripsi:</strong> {{ $service->description }}</p>
        <p>
            <strong>Harga:</strong> Rp
            {{ number_format($service->price, 0, ',', '.') }}
        </p>

        @if($service->images)
        <h2 class="text-xl mt-4">Gambar</h2>
        <div class="flex space-x-2 mt-2">
            @foreach(json_decode($service->images) as $img)
            <img src="{{ asset('storage/'.$img) }}"
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
                       placeholder="Masukkan nomor telepon Anda"
                       required>
            </label>
            <label class="block mt-2">
                Alamat Pengiriman / Lokasi:
                <textarea name="customer_address"
                          class="border rounded px-2 py-1 w-full"
                          placeholder="Masukkan alamat Anda"
                          required></textarea>
            </label>
            <!-- Input nomor telepon -->


            <!-- Input catatan khusus -->
            <label class="block mt-2">
                Catatan / Instruksi:
                <textarea name="note"
                          class="border rounded px-2 py-1 w-full"
                          placeholder="Misal: jam layanan, instruksi khusus"
                          rows="3"></textarea>
            </label>


            <label class="block mt-2">
                Metode Pembayaran:
                <select name="payment_method"
                        id="paymentMethod"
                        class="border rounded px-2 py-1 w-full">
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="e_wallet">E-Wallet (OVO, GoPay, Dana)</option>
                    <option value="dummy_gateway">Gateway Dummy</option>
                </select>
            </label>
            <p class="mt-40">
                Total Harga: Rp <span id="totalPrice">{{ number_format($service->price, 0, ',', '.') }}</span>
            </p>

            <!-- Info tambahan tergantung metode -->
            <div id="paymentInfo"
                 class="mt-2 p-2 bg-gray-100 rounded">
                Silakan pilih metode pembayaran.
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
        const price = @json($service -> price);

        const paymentInfo = document.getElementById('paymentInfo');
        const paymentMethod = document.getElementById('paymentMethod');

        function calculateTotal() {
            const qty = document.getElementById('quantity').value || 1;
            const total = price * qty;
            document.getElementById('totalPrice').textContent = total.toLocaleString('id-ID');
        }

        paymentMethod.addEventListener('change', () => {
            const method = paymentMethod.value;
            if (method === 'bank_transfer') {
                paymentInfo.textContent = "Transfer ke rekening BCA 123-456-789 a.n. PT. Contoh.";
            } else if (method === 'e_wallet') {
                paymentInfo.textContent = "Bayar menggunakan OVO, GoPay, atau Dana ke nomor 0812-3456-7890.";
            } else if (method === 'dummy_gateway') {
                paymentInfo.textContent = "Simulasi gateway online. Klik 'Bayar & Pesan' untuk checkout dummy.";
            }
        });

        // Inisialisasi info pertama
        calculateTotal();
        paymentMethod.dispatchEvent(new Event('change'));
    </script>

</x-app-layout>
