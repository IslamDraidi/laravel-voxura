<x-layout :title="$page->meta_title ?? $page->title">
    <div style="max-width:860px;margin:3rem auto;padding:0 1.5rem;">
        <div style="margin-bottom:1.5rem;">
            <a href="/" style="color:var(--link);font-size:14px;">← Back to Store</a>
        </div>

        <article>
            <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;
                        color:var(--dark);margin-bottom:1.5rem;line-height:1.2;">
                {{ $page->title }}
            </h1>

            @if($page->meta_description)
                <p style="color:var(--muted);font-size:15px;margin-bottom:1.5rem;
                           border-left:3px solid var(--orange);padding-left:0.75rem;">
                    {{ $page->meta_description }}
                </p>
            @endif

            <div style="font-size:15px;line-height:1.8;color:var(--dark);">
                {!! $page->content !!}
            </div>
        </article>
    </div>
</x-layout>
