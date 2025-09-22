<x-app-layout>
    <div class="container mx-auto py-6 max-w-4xl">
        <h1 class="text-2xl font-bold mb-4">Daftar Pesanan Saya</h1>

        @if($orders->isEmpty())
        <p>Belum ada pesanan.</p>
        @else
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">ID Order</th>
                    <th class="border px-4 py-2">Jasa</th>
                    <th class="border px-4 py-2">
                        @if(auth()->user()->role === 'customer')
                        Seller
                        @else
                        Pembeli
                        @endif
                    </th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Metode Bayar</th>
                    <th class="border px-4 py-2">Alamat</th>
                    <th class="border px-4 py-2">Telepon</th>
                    <th class="border px-4 py-2">Catatan</th>
                    <th class="border px-4 py-2">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $order->id }}</td>
                    <td class="border px-4 py-2">{{ $order->service->title }}</td>
                    <td class="border px-4 py-2">
                        @if(auth()->user()->role === 'customer')
                        {{ $order->seller->name }}
                        @else
                        {{ $order->customer->name }}
                        @endif
                    </td>
                    <td class="border px-4 py-2">Rp {{ number_format($order->price,0,',','.') }}</td>
                    <td class="border px-4 py-2">{{ $order->payment_method }}</td>
                    <td class="border px-4 py-2">{{ $order->customer_address }}</td>
                    <td class="border px-4 py-2">{{ $order->customer_phone }}</td>
                    <td class="border px-4 py-2">{{ $order->note ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</x-app-layout>
