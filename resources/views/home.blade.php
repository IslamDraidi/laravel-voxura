<x-layout mainClass="full-width">


    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow mt-8">
            <div class="card-body">
                <h1 class="text-3xl font-bold">Welcome to Voxura!</h1>
                <p class="mt-4 text-base-content/60">This is your brand new voxura application.</p>
            </div>
        </div>
    </div>
</x-layout>


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
