<x-app-layout>
    <div class="container mx-auto p-4 md:p-8 max-w-7xl">
        @php
            $user = auth()->user();
        @endphp

        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">
            @if ($user->role === 'customer')
                Pesanan Saya
            @elseif($user->role === 'seller')
                Pesanan Masuk dan Riwayat Pesanan
            @endif
        </h1>

        @if ($orders->isEmpty())
            <div class="bg-white p-8 rounded-lg text-center text-gray-500 border border-gray-200">
                <p class="text-lg">Belum ada pesanan yang Anda buat atau terima.</p>
                <p class="mt-2 text-gray-400">Silakan jelajahi layanan untuk memulai.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($orders as $order)
                    @php
                        $userId = auth()->id();
                        $isCustomerOrder = $order->customer_id === $userId;
                        $isSellerOrder = $order->seller_id === $userId;

                        $party = $isCustomerOrder
                            ? $order->seller->full_name ?? 'N/A'
                            : $order->customer->full_name ?? 'N/A';

                        $partyLabel = $isCustomerOrder ? 'Penjual' : 'Pembeli';

                        $statusClass =
                            [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'accepted' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-blue-100 text-blue-800',
                                'canceled' => 'bg-red-100 text-red-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ][strtolower($order->status)] ?? 'bg-gray-100 text-gray-800';

                        $statusIcon =
                            [
                                'pending' =>
                                    'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                                'accepted' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                'completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                'canceled' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                'rejected' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                            ][strtolower($order->status)] ??
                            'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                    @endphp

                    <div
                         class="bg-white rounded-xl p-6 border border-gray-200 transition-transform duration-200 hover:-translate-y-1">
                        <div class="flex justify-between items-center mb-4">
                            <a href="{{ route('orders.show', $order->id) }}"
                               class="text-xl font-bold text-gray-800 hover:text-primary leading-tight hover:underline">
                                {{ $order->service->title ?? 'Layanan tidak ditemukan' }}
                            </a>
                            <span
                                  class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }} capitalize">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="{{ $statusIcon }}" />
                                </svg>
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><strong>{{ $partyLabel }}:</strong> {{ $party }}</p>
                            <p><strong>Harga:</strong> Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                            <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p><strong>Metode Bayar:</strong> {{ ucfirst($order->payment_method) }}</p>
                        </div>

                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                {{-- Aksi Customer --}}

                                @if ($isCustomerOrder)
                                    @if (strtolower($order->status) === 'pending')
                                        <form action="{{ route('orders.cancel', $order->id) }}"
                                              method="POST"
                                              class="col-span-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                                Batalkan Pesanan
                                            </button>
                                        </form>
                                    @elseif(strtolower($order->status) === 'accepted')
                                        <form action="{{ route('orders.complete', $order->id) }}"
                                              method="POST"
                                              class="col-span-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="w-full flex items-center justify-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors">
                                                Tandai Selesai
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                {{-- Aksi Seller --}}
                                @if ($isSellerOrder && strtolower($order->status) === 'pending')
                                    <form action="{{ route('orders.accept', $order->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors">
                                            Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('orders.reject', $order->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                            Tolak
                                        </button>
                                    </form>
                                @endif

                                {{-- Chat --}}
                                <form action="{{ route('conversations.start') }}"
                                      method="POST"
                                      class="col-span-2 mt-2">
                                    @csrf
                                    <input type="hidden"
                                           name="seller_id"
                                           value="{{ $order->seller_id }}">
                                    <input type="hidden"
                                           name="product_id"
                                           value="{{ $order->service_id }}">
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        Chat
                                    </button>
                                </form>
                            </div>

                            {{-- Form Review --}}
                            @if ($isCustomerOrder && strtolower($order->status) === 'completed' && $order->service)
                                @php
                                    $userReview = $order->service->reviews->where('customer_id', $userId)->first();
                                @endphp

                                @if (!$userReview)
                                    <div class="bg-gray-100 p-4 rounded-lg mt-4">
                                        <h4 class="font-bold text-gray-800 mb-2">Beri Ulasan</h4>
                                        <form action="{{ route('reviews.store', $order->service->slug) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mb-2">
                                                <input type="hidden"
                                                       name="rating"
                                                       id="rating-input-{{ $order->id }}">
                                                <div class="flex items-center space-x-1 rating-stars cursor-pointer"
                                                     id="rating-container-{{ $order->id }}">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="h-8 w-8 text-gray-400 star-icon"
                                                             data-rating="{{ $i }}"
                                                             fill="currentColor"
                                                             viewBox="0 0 20 20">
                                                            <path
                                                                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                            </path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <label for="comment-{{ $order->id }}"
                                                       class="block text-sm text-gray-700">Komentar</label>
                                                <textarea id="comment-{{ $order->id }}"
                                                          name="comment"
                                                          rows="2"
                                                          class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"></textarea>
                                            </div>
                                            <button type="submit"
                                                    class="w-full px-4 py-2 mt-2 bg-gray-700 text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                                                Kirim Ulasan
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="bg-gray-100 p-4 rounded-lg mt-4">
                                        <h4 class="font-bold text-gray-800 mb-2">Ulasan Anda</h4>
                                        <div class="flex items-center text-sm text-gray-600 mb-1">
                                            <strong>Rating:</strong>
                                            <div class="flex ml-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $userReview->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                         fill="currentColor"
                                                         viewBox="0 0 20 20">
                                                        <path
                                                              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                        </path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600"><strong>Komentar:</strong>
                                            {{ $userReview->comment ?? '-' }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.rating-stars').forEach(container => {
                const orderId = container.id.split('-')[2];
                const hiddenInput = document.getElementById(`rating-input-${orderId}`);
                const stars = container.querySelectorAll('.star-icon');

                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        const ratingValue = star.dataset.rating;
                        hiddenInput.value = ratingValue;

                        stars.forEach(s => {
                            if (s.dataset.rating <= ratingValue) {
                                s.classList.add('text-yellow-400');
                                s.classList.remove('text-gray-400');
                            } else {
                                s.classList.add('text-gray-400');
                                s.classList.remove('text-yellow-400');
                            }
                        });
                    });
                });
            });
        });
    </script>
</x-app-layout>
