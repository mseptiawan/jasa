<x-app-layout>
    <div class="container mx-auto py-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Invoice Pesanan #{{ $order->id }}</h1>

        <p><strong>Nama Layanan:</strong> {{ $order->service->title }}</p>
        <p><strong>Harga Layanan:</strong> Rp {{ number_format($order->price, 0, ',', '.') }}</p>
        <p><strong>Fee Platform (5%):</strong> Rp {{ number_format($order->platform_fee, 0, ',', '.') }}</p>
        <p><strong>Total Bayar:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }} (simulasi)</p>
        <p><strong>Alamat:</strong> {{ $order->customer_address }}</p>
        <p><strong>Nomor Telepon:</strong> {{ $order->customer_phone }}</p>
        <p><strong>Catatan:</strong> {{ $order->note }}</p>
        <a href="{{ route('orders.invoice', $order) }}"
           class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Download PDF
        </a>

        <div class="mt-4">
            <a href="{{ route('services.index') }}"
               class="text-blue-500 hover:underline">← Kembali ke daftar layanan</a>
        </div>
    </div>
</x-app-layout>
