<section id="about" class="about-section">
    <div class="about-container">

        {{-- العنوان --}}
        <div class="about-header">
            <h2 class="about-title">
                Why <span>Voxura</span>
            </h2>
            <p class="about-subtitle">
                {{ __('general.about_subtitle') }}
            </p>
        </div>

        {{-- الكاردات --}}
        <div class="about-grid">

            {{-- كارد 1 --}}
            <div class="about-card">
                <div class="about-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="6"/>
                        <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                    </svg>
                </div>
                <h3>{{ __('general.about_quality_title') }}</h3>
                <p>{{ __('general.about_quality_desc') }}</p>
            </div>

            {{-- كارد 2 --}}
            <div class="about-card">
                <div class="about-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                </div>
                <h3>{{ __('general.about_cutting_title') }}</h3>
                <p>{{ __('general.about_cutting_desc') }}</p>
            </div>

            {{-- كارد 3 --}}
            <div class="about-card">
                <div class="about-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <h3>{{ __('general.about_trusted_title') }}</h3>
                <p>{{ __('general.about_trusted_desc') }}</p>
            </div>

        </div>
    </div>
</section>