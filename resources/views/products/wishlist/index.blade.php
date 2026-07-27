@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">

    <h1 class="text-xl font-bold mb-6">Wishlist Saya</h1>

    @if($wishlists->isEmpty())
        <p class="text-gray-500">Belum ada produk di wishlist.</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

            @foreach($wishlists as $item)
                <x-product-card :product="$item->product" />
            @endforeach

        </div>
    @endif

</div>
@endsection