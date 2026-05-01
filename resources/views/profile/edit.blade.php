<x-layout title="Settings">
<style>
.profile-page { padding-top: 100px; padding-bottom: 4rem; max-width: 680px; margin: 0 auto; }

.profile-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 2.75rem);
    font-weight: 800;
    color: var(--gray-900);
    letter-spacing: -0.03em;
    margin-bottom: 0.25rem;
}
.profile-heading .accent { color: var(--orange); }
.profile-sub { color: var(--gray-500); font-size: 0.95rem; margin-bottom: 2.5rem; }

/* ── Avatar ── */
.profile-avatar-wrap {
    display: flex; align-items: center; gap: 1.25rem;
    margin-bottom: 2.5rem;
}
.profile-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    background: var(--orange); color: #fff;
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 16px rgba(234,88,12,0.28);
}
.profile-avatar-info p:first-child {
    font-weight: 700; font-size: 1rem; color: var(--gray-900);
}
.profile-avatar-info p:last-child {
    font-size: 0.82rem; color: var(--gray-400); margin-top: 0.15rem;
}

/* ── Cards ── */
.profile-card {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius);
    overflow: hidden;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow-sm);
}

.profile-card-header {
    padding: 1.1rem 1.5rem;
    background: var(--gray-50);
    border-bottom: 1.5px solid var(--gray-100);
    display: flex; align-items: center; gap: 0.6rem;
}
.profile-card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem; font-weight: 800;
    color: var(--gray-900);
}

.profile-card-body { padding: 1.5rem; }

/* ── Form ── */
.form-group { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1rem; }
.form-group:last-of-type { margin-bottom: 0; }

.form-label {
    font-size: 0.75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
    color: var(--gray-500);
}

.form-input {
    padding: 0.7rem 0.9rem;
    border: 1.5px solid var(--gray-200);
    border-radius: 0.5rem;
    font-size: 0.9rem;
    font-family: 'DM Sans', sans-serif;
    color: var(--gray-900);
    outline: none;
    transition: border-color 0.15s;
    width: 100%;
    background: #fff;
}
.form-input:focus { border-color: var(--orange); }

.form-error { font-size: 0.75rem; color: #ef4444; margin-top: 0.2rem; }

.form-hint { font-size: 0.75rem; color: var(--gray-400); margin-top: 0.2rem; }

.btn-save {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: var(--orange); color: #fff; border: none;
    padding: 0.65rem 1.75rem; border-radius: 999px;
    font-size: 0.88rem; font-weight: 700; cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.15s, transform 0.1s;
    margin-top: 1.25rem;
    box-shadow: 0 3px 12px rgba(234,88,12,0.22);
}
.btn-save:hover { background: var(--orange-dark); }
.btn-save:active { transform: scale(0.98); }

/* ── Danger Zone ── */
.danger-zone {
    background: #fff;
    border: 1.5px solid #fecaca;
    border-radius: var(--radius);
    padding: 1.25rem 1.5rem;
    margin-top: 2rem;
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap; gap: 1rem;
}
.danger-zone-info p:first-child {
    font-weight: 700; color: var(--gray-900); font-size: 0.9rem;
}
.danger-zone-info p:last-child {
    font-size: 0.78rem; color: var(--gray-400); margin-top: 0.2rem;
}
.btn-danger {
    background: none; border: 1.5px solid #fecaca;
    color: #ef4444; padding: 0.5rem 1.1rem;
    border-radius: 999px; font-size: 0.82rem; font-weight: 700;
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    transition: background 0.15s;
}
.btn-danger:hover { background: #fee2e2; }
</style>

<div class="profile-page">

    <h1 class="profile-heading">Account <span class="accent">Settings</span></h1>
    <p class="profile-sub">Manage your profile information and password.</p>

    {{-- Avatar --}}
    <div class="profile-avatar-wrap">
        <div class="profile-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="profile-avatar-info">
            <p>{{ auth()->user()->name }}</p>
            <p>{{ auth()->user()->email }}</p>
        </div>
    </div>

    {{-- ── Profile Info ── --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <span>👤</span>
            <p class="profile-card-title">Personal Information</p>
        </div>
        <div class="profile-card-body">
            <form method="POST" action="/profile/info">
                @csrf @method('PATCH')

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input"
                           value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input"
                           value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn-save">Save Changes</button>
            </form>
        </div>
    </div>

    {{-- ── Password ── --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <span>🔒</span>
            <p class="profile-card-title">Change Password</p>
        </div>
        <div class="profile-card-body">
            <form method="POST" action="/profile/password">
                @csrf @method('PATCH')

                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-input"
                           placeholder="Enter current password" required>
                    @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-input"
                           placeholder="At least 8 characters" required>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    <p class="form-hint">Minimum 8 characters.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-input"
                           placeholder="Repeat new password" required>
                </div>

                <button type="submit" class="btn-save">Update Password</button>
            </form>
        </div>
    </div>

    {{-- ── Try-Ons ── --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <span>👗</span>
            <p class="profile-card-title">My Virtual Try-Ons</p>
        </div>
        <div class="profile-card-body">
            <p style="color:#6B6B6B;font-size:0.92rem;margin-bottom:1rem;line-height:1.5;">
                Browse your past virtual try-ons, view them in 3D, or remove ones you no longer want.
            </p>
            <a href="{{ route('tryon.history') }}" class="btn-save"
               style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;width:auto;">
                View My Try-Ons →
            </a>
        </div>
    </div>

    {{-- ── Danger Zone ── --}}
    <div class="danger-zone">
        <div class="danger-zone-info">
            <p>Sign out of your account</p>
            <p>You'll need to sign in again to access your account.</p>
        </div>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="btn-danger">Sign Out</button>
        </form>
    </div>

</div>
</x-layout>