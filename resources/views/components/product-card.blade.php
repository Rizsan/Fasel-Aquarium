{{-- resources/views/components/product-card.blade.php --}}

<div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-100 hover:-translate-y-1">

    {{-- Product Image (klik ke detail) --}}
    <a href="{{ route('products.show', $product->id) }}">
        <div class="relative overflow-hidden bg-gray-50 aspect-square">
            <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
            >

            {{-- Badge --}}
            <span class="absolute top-3 left-3 bg-blue-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                Baru
            </span>

            {{-- Quick View --}}
            <div class="absolute inset-x-0 bottom-0 p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="w-full bg-gray-900 bg-opacity-80 hover:bg-opacity-100 text-white text-xs font-semibold py-2 rounded-lg text-center transition backdrop-blur-sm">
                    Lihat Detail
                </div>
            </div>
        </div>
    </a>

    {{-- Wishlist Button --}}
    <div
        x-data="{ liked: {{ $isWishlisted ? 'true' : 'false' }}, loading: false }"
        class="absolute top-3 right-3"
    >
        @auth
            <button
                @click.prevent="
                    loading = true;
                    fetch('{{ route('wishlist.toggle')}}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ product_id: {{ $product->id }} })
                    })
                    .then(res => res.json())
                    .then(data => {
                        liked = !liked;
                        loading = false;
                    })
                    .catch(() => {
                        loading = false;
                    });
                "
                class="w-8 h-8 bg-white rounded-full shadow flex items-center justify-center transition hover:bg-red-50 relative"
            >
                {{-- Heart Icon --}}
                <svg
                    class="w-4 h-4 transition transform duration-200"
                    :class="liked ? 'text-red-500 fill-red-500 scale-125' : 'text-gray-400 scale-100'"
                    :fill="liked ? 'currentColor' : 'none'"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>

                {{-- Loading Spinner --}}
                <div x-show="loading" class="absolute inset-0 flex items-center justify-center">
                    <div class="w-3 h-3 border-2 border-gray-300 border-t-red-500 rounded-full animate-spin"></div>
                </div>
            </button>
        @else
            <a href="{{ route('login') }}" class="w-8 h-8 bg-white rounded-full shadow flex items-center justify-center">
                ❤️
            </a>
        @endauth
    </div>

    {{-- Product Info --}}
    <div class="p-4">
        <a href="{{ route('products.show', $product->id) }}">
            <h3 class="font-semibold text-gray-800 text-sm leading-snug line-clamp-2 group-hover:text-blue-600 transition">
                {{ $product->name }}
            </h3>
        </a>

        @if($product->description)
            <p class="text-gray-400 text-xs mt-1 line-clamp-1">
                {{ $product->description }}
            </p>
        @endif

        {{-- Rating --}}
        <div class="flex items-center gap-1 mt-2">
            @for($i = 0; $i < 5; $i++)
                <svg class="w-3.5 h-3.5 {{ $i < 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endfor
            <span class="text-gray-400 text-xs ml-1">(4.0)</span>
        </div>

        {{-- Price & Cart --}}
        <div class="flex items-center justify-between mt-3">
            <div>
                <p class="text-blue-600 font-bold text-base">
                    {{ $product->formatted_price ?? \App\Helpers\FormatHelper::rupiah($product->price) }}
                </p>
                <p class="text-gray-400 text-xs line-through">
                    Rp {{ number_format($product->price * 1.2, 0, ',', '.') }}
                </p>
            </div>

            @auth
                <div x-data="{ sending: false }">
                    <button
                        type="button"
                        @click="
                            if (sending) return;
                            sending = true;

                            fetch('{{ route('cart.add') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    product_id: {{ $product->id }},
                                    quantity: 1
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                sending = false;
                                
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Produk telah ditambahkan ke keranjang.',
                                        showConfirmButton: false,
                                        timer: 1000,
                                        customClass: {
                                            popup: 'rounded-2xl'
                                        }
                                    });
                                    
                                    // FIX UTAMA: Mencari element ID counter di header dan merubah teks angkanya secara langsung
                                    const cartBadge = document.getElementById('header-cart-count');
                                    if (cartBadge && typeof data.cart_count !== 'undefined') {
                                        cartBadge.textContent = data.cart_count;
                                    }
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: data.message || 'Gagal menambahkan produk.',
                                        confirmButtonColor: '#2563eb',
                                        customClass: {
                                            popup: 'rounded-2xl'
                                        }
                                    });
                                }
                            })
                            .catch(err => {
                                sending = false;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan, coba beberapa saat lagi.',
                                    confirmButtonColor: '#2563eb',
                                    customClass: {
                                        popup: 'rounded-2xl'
                                    }
                                });
                            });
                        "
                        @if($product->stock <= 0) disabled @endif
                        class="flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-lg transition active:scale-95 relative"
                        :class="sending || {{ $product->stock }} <= 0 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 text-white'"
                    >
                        <span x-show="!sending">🛒 {{ $product->stock > 0 ? 'Tambah' : 'Habis' }}</span>
                        <span x-show="sending" class="flex items-center gap-1" x-cloak>
                            <div class="w-3 h-3 border-2 border-gray-400 border-t-blue-600 rounded-full animate-spin"></div>
                            Proses...
                        </span>
                    </button>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg transition active:scale-95">
                    Login
                </a>
            @endauth
        </div>
    </div>
</div>