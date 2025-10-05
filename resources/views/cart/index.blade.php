<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-10">
        <h1 class="text-2xl font-bold mb-6">Keranjang Saya</h1>

        @if ($carts->isEmpty())
            <p>Keranjang kosong.</p>
        @else
            <table class="w-full text-left border">
                <thead>
                    <tr>
                        <th class="p-2 border">Jasa</th>
                        <th class="p-2 border">Harga</th>
                        <th class="p-2 border">Aksi</th>
                        <th class="p-2 border">Pesan</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $cart)
                        <tr>
                            <td class="p-2 border">
                                <a href="{{ route('services.show', $cart->service->slug) }}"
                                   class="text-blue-600 hover:underline">
                                    {{ $cart->service->title }}
                                </a>
                            </td>

                            <td class="p-2 border">Rp {{ number_format($cart->service->price, 0, ',', '.') }}</td>
                            <td class="p-2 border">
                                <form action="{{ route('cart.remove', $cart->service_id) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="text-red-500 hover:text-red-700">
                                        <!-- Heroicon Trash -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5 inline-block"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('orders.create', ['service' => $cart->service->slug]) }}">
                                    Pesan
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('cart.clear') }}"
                  method="POST"
                  class="mt-4">
                @csrf
                <button class="bg-red-500 text-white px-4 py-2 rounded">Kosongkan Keranjang</button>
            </form>
        @endif
    </div>
</x-app-layout>
