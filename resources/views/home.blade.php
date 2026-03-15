<x-layout mainClass="full-width">

    {{-- Hero --}}
    <x-hero />

    {{-- Featured Products --}}
    <div style="background:#fff">
        @foreach($featuredProducts as $index => $product)
            <x-featured-product :product="$product" :index="$index" />
        @endforeach
    </div>

    {{-- Product Grid --}}
    <x-product-grid :products="$products" />

    {{-- About --}}
    <x-about />

    {{-- Contact --}}
    <x-contact />

</x-layout>