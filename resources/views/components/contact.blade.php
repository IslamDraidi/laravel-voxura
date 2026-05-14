<section id="contact" class="contact-section">
    <div class="contact-container">

        {{-- العنوان --}}
        <div class="contact-header">
            <h2 class="contact-title">
                {{ __('general.contact_title') }}
            </h2>
            <p class="contact-subtitle">
                {{ __('general.contact_subtitle') }}
            </p>
        </div>

        <div class="contact-grid">

            {{-- الفورم --}}
            <div class="contact-form-wrap">

                @if(session('contact_success'))
                    <div style="background:rgba(232,98,26,0.1);border:1px solid var(--orange);border-radius:10px;padding:14px 18px;color:var(--orange);margin-bottom:20px;font-size:14px;">
                        {{ session('contact_success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background:rgba(220,38,38,0.1);border:1px solid #dc2626;border-radius:10px;padding:14px 18px;color:#dc2626;margin-bottom:20px;font-size:14px;">
                        <ul style="margin:0;padding:0;list-style:none;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
                    @csrf

                    <div class="form-group">
                        <label>{{ __('general.contact_name_label') }}</label>
                        <input type="text" name="name"
                               placeholder="{{ __('general.contact_name_placeholder') }}" required />
                    </div>

                    <div class="form-group">
                        <label>{{ __('general.contact_email_label') }}</label>
                        <input type="email" name="email"
                               placeholder="your@email.com" required />
                    </div>

                    <div class="form-group">
                        <label>{{ __('general.contact_message_label') }}</label>
                        <textarea name="message" rows="5"
                                  placeholder="{{ __('general.contact_msg_placeholder') }}" required></textarea>
                    </div>

                    <button type="submit" class="btn-contact">
                        {{ __('general.contact_send') }}
                    </button>

                </form>
            </div>

            {{-- معلومات التواصل --}}
            <div class="contact-info">

                {{-- Email --}}
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="16" x="2" y="4" rx="2"/>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </div>
                    <div>
                        <h3>{{ __('general.contact_email_label') }}</h3>
                        <p>support@voxura.com</p>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.58 5.1 2 2 0 0 1 3.55 3h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 10.9a16 16 0 0 0 6 6l.9-.9a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 19z"/>
                        </svg>
                    </div>
                    <div>
                        <h3>{{ __('general.phone') }}</h3>
                        <p>+970 56 804 6647</p>
                    </div>
                </div>

                {{-- Location --}}
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div>
                        <h3>Location</h3>
                        <p>Ramallah, PA</p>
                    </div>
                </div>

                {{-- Social Media --}}
                <div class="contact-social">
                    <p>Follow us on social media</p>
                    <div class="social-buttons">
                        <a href="#" class="btn-social">Twitter</a>
                        <a href="#" class="btn-social">Instagram</a>
                        <a href="#" class="btn-social">LinkedIn</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
