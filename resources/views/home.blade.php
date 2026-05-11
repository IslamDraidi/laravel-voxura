<x-layout mainClass="full-width">

    {{-- Hero --}}
    <x-hero />

    {{-- Featured Products --}}
    <div id="next-section" style="background:#fff">
        @foreach($featuredProducts as $index => $product)
            <x-featured-product :product="$product" :index="$index" />
        @endforeach
    </div>

    {{-- Product Grid with filter drawer --}}
    <x-product-grid
        :products="$products"
        :activeCategory="$activeCategory"
        :categories="$categories"
        :sort="$sort"
        :sizes="$sizes"
        :colors="$colors"
        :priceRange="$priceRange"
        :categoryIds="$categoryIds"
        :availableSizes="$availableSizes"
        :availableColors="$availableColors"
        :productCounts="$productCounts"
    />

    {{-- About --}}
    <x-about />

    {{-- Contact --}}
    <x-contact />

</x-layout>
