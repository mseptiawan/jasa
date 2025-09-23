<div class="container mx-auto py-6 max-w-md bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4 text-center">Informasi Pembayaran</h1>

    <div class="mb-4">
        <p>Hai <span class="font-semibold">{{ auth()->user()->full_name }}</span></p>
        <p>Nomor HP: <span class="font-semibold">{{ $order->customer_phone }}</span></p>
    </div>

    <div class="mb-4 p-4 bg-gray-100 rounded">
        <p>Total Bayar: <strong class="text-lg text-green-600">Rp
                {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
        <p>Metode: <strong>{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</strong></p>
        <p>Paket Layanan: <strong>{{ $service->title }}</strong></p>
    </div>
    <p>No Rekening (VA): <strong>8808 9206 0119 0836</strong></p>
    <p>Simulasi pembayaran online sedang diproses...</p>
    <div class="mt-2 animate-pulse text-gray-500">Mohon tunggu sebentar</div>


    <p class="mt-4 text-gray-600 text-center">Pembayaran akan otomatis dikonfirmasi dalam beberapa detik.</p>
</div>

<script>
    @if ($order->payment_method == 'dummy_gateway')
        setTimeout(() => {
            // redirect ke halaman konfirmasi pembayaran dummy
            window.location.href = "{{ route('orders.store', $order->id) }}";
        }, 15000); // 3 detik
    @endif
</script>
