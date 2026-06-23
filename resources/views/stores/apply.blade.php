<x-layout title="{{ __('general.apply_to_join') }}">

<div style="max-width:600px;margin:80px auto;padding:0 32px;text-align:center;">
    <h1 style="font-size:32px;font-weight:800;color:#1a1a1a;margin-bottom:12px;font-family:'Playfair Display',serif;">
        {{ __('general.want_store_on_voxura') }}
    </h1>
    <p style="color:#666;font-size:15px;line-height:1.7;margin-bottom:32px;">
        Interested in listing your store on Voxura? Get in touch with our team.
    </p>
    <a href="{{ route('home') }}#contact"
       style="background:var(--orange);color:#fff;border-radius:8px;padding:14px 32px;font-weight:700;text-decoration:none;font-size:15px;">
        Contact Us
    </a>
</div>

</x-layout>
