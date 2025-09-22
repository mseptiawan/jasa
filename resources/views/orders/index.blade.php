<x-app-layout>
    <div class="container mx-auto py-6 max-w-5xl">
        <h1 class="text-2xl font-bold mb-4">Pesanan</h1>

        @if($orders->isEmpty())
        <p>Belum ada pesanan.</p>
        @else
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Jasa</th>
                    <th class="border px-4 py-2">
                        @if(auth()->user()->role === 'customer') Seller @else Pembeli @endif
                    </th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Metode Bayar</th>
                    <th class="border px-4 py-2">Alamat</th>
                    <th class="border px-4 py-2">Telepon</th>
                    <th class="border px-4 py-2">Catatan</th>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Aksi</th>
                    <th class="border px-4 py-2">Chat / Review</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="align-top">
                    <td class="border px-4 py-2">{{ $order->service->title ?? '-' }}</td>
                    <td class="border px-4 py-2">
                        @if(auth()->user()->role === 'customer')
                        {{ $order->seller->full_name ?? '-' }}
                        @else
                        {{ $order->customer->full_name ?? '-' }}
                        @endif
                    </td>
                    <td class="border px-4 py-2">Rp {{ number_format($order->price,0,',','.') }}</td>
                    <td class="border px-4 py-2">{{ $order->payment_method }}</td>
                    <td class="border px-4 py-2">{{ $order->customer_address ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $order->customer_phone ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $order->note ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($order->status) }}</td>

                    {{-- Aksi customer / seller --}}
                    <td class="border px-4 py-2 space-y-1">
                        @if(auth()->user()->role === 'customer')
                        @if(strtolower($order->status) === 'pending')
                        <form action="{{ route('orders.cancel', $order->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                Cancel
                            </button>
                        </form>
                        @elseif(strtolower($order->status) === 'accepted')
                        <form action="{{ route('orders.complete', $order->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                Complete
                            </button>
                        </form>
                        @endif
                        @else
                        @if(strtolower($order->status) === 'pending')
                        <form action="{{ route('orders.accept', $order->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                Accept
                            </button>
                        </form>
                        <form action="{{ route('orders.reject', $order->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                Reject
                            </button>
                        </form>
                        @endif
                        @endif
                    </td>

                    {{-- Chat + Review --}}
                    <td class="border px-4 py-2 space-y-2">
                        {{-- Chat --}}
                        <form action="{{ route('conversations.start') }}"
                              method="POST">
                            @csrf
                            <input type="hidden"
                                   name="seller_id"
                                   value="{{ $order->seller_id }}">
                            <input type="hidden"
                                   name="product_id"
                                   value="{{ $order->service_id }}">
                            <button class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Chat
                            </button>
                        </form>

                        {{-- Review only for completed orders --}}
                        @if(auth()->user()->role === 'customer' && strtolower($order->status) === 'completed' &&
                        $order->service)
                        @php
                        $userReview = $order->service->reviews->where('customer_id', auth()->id())->first();
                        @endphp

                        @if(!$userReview)
                        <form action="{{ route('reviews.store', $order->service->slug) }}"
                              method="POST"
                              class="mt-2">
                            @csrf
                            @method('PATCH')
                            <label>Rating (1-5)</label>
                            <input type="number"
                                   name="rating"
                                   min="1"
                                   max="5"
                                   required>
                            <label>Comment</label>
                            <textarea name="comment"></textarea>
                            <button type="submit"
                                    class="px-2 py-1 bg-indigo-500 text-white rounded hover:bg-indigo-600">
                                Submit Review
                            </button>
                        </form>
                        @else
                        <div class="mt-2 p-2 bg-gray-100 rounded">
                            <strong>Rating:</strong> {{ $userReview->rating }} / 5 <br>
                            <strong>Comment:</strong> {{ $userReview->comment ?? '-' }}
                        </div>
                        @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</x-app-layout>
