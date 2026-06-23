# CLAUDE.md — Voxura

> Authoritative reference for AI assistants. Update when adding features or changing architecture.

When creating git commits, do not add Co-Authored-By lines.

---

## PROJECT OVERVIEW

**Voxura** — Fashion e-commerce platform (Laravel 12.x, PHP ^8.2, team of 4).

Core features: virtual try-on (3D body scan + garment fitting), AI-generated 3D product models, full EN/AR multilingual support.

Key external services: Tap Payments · HuggingFace (QwenVL) · Replicate (TRELLIS/HunyuanD/Rodin) · fal.ai (SAM-3D body) · Three.js (viewer3d.js) · trimesh (Python garment fitting)

---

## ARCHITECTURE RULES

### General
- Queue/Session/Cache driver: `database`
- Scheduler registered in `bootstrap/app.php` (Laravel 11/12 style — no `Console/Kernel.php`)
- Images stored in `public/images/` — NOT `storage/`
- Product images relation is `images()` — NOT `productImages()`
- All seeders use `firstOrCreate()` or `updateOrCreate()` — never plain `create()`
- Soft deletes on `Product` model
- Never use `@extends` in any Blade view

### Storage Paths
| Content | Path |
|---------|------|
| Product 3D models | `storage/app/public/models/{product_id}/{filename}.glb` |
| Body models | `storage/app/public/bodies/{user_id}/body_{...}.glb` |
| Try-on results | `storage/app/public/tryon_results/{user_id}/tryon_{id}.glb` |
| Product images | `public/images/{filename}` |
| User try-on photos | `storage/app/public/tryons/{user_id}/` |

### Blade Layouts
- Storefront: `<x-layout title="...">` (optionally `mainClass="full-width"`)
- Admin: `<x-admin-layout title="..." section="..." active="...">`

### Controllers
- Return `response()->json()` for all JS-called routes
- Always keep `redirect()` fallback for non-AJAX form flows
- Verify ownership before exposing user-specific resources; use `abort(403)` for unauthorized
- `AdminController` is monolithic (~1100+ lines) — do not split unless explicitly asked
- Only dedicated admin sub-controller: `Admin/AdminRefundController`

### AI Services
- Never call AI APIs directly from controllers — always go through `app/Services/AI/`
- 3D model generation: Replicate API via `TrellisService` (not HuggingFace for TRELLIS)
- Image selection: HuggingFace via `QwenVLService`
- Body generation: fal.ai queue API via `Sam3DBodyFalService` (`TRYON_BODY_PROVIDER=fal`)
- `MODEL3D_PROVIDER` selects backend: `trellis` | `trellis2` | `hunyuan` | `rodin`
- Never expose `HF_API_TOKEN`, `FAL_KEY`, or `REPLICATE_API_TOKEN` in frontend

### Queue Jobs
- `Generate3DModelJob`: `tries=3`, `timeout=600`, `backoff=30`
- `ProcessVirtualTryOnJob`: `tries=2`, `timeout=300`, `backoff=60`
- Always `refresh()` model at the start of `handle()` to avoid stale data
- Job failure **must** update model status to `'failed'`
- Product save must **never** fail because of 3D generation
- Discard silently on `ModelNotFoundException`

### Payment
- Active gateway: Tap Payments (`tap.enabled = true`)
- PayPal exists but is disabled (`paypal.enabled = false`)
- `config/payment.php` default key is `'paypal'` — misleading; always set `PAYMENT_GATEWAY=tap` in `.env`
- Webhooks are CSRF-exempt: `webhooks/payment/*` excluded in `bootstrap/app.php`

### Security
- All sensitive keys in `.env` only
- CSRF token required in all AJAX POST requests (`meta[name=csrf-token]` in both layouts)
- User photos stored per `user_id` directory — never cross-user access
- Body model saved to user record only if `photo_consent = true`

### Localization
- Supported locales: `en`, `ar`
- Language switch: `POST /language/switch` → `LanguageController@switch`
- Middleware: `SetLocale` (reads `user->preferred_locale` or `session('locale')`)
- RTL fully implemented — `dir`/`lang` on `<html>` set dynamically; Tajawal font for Arabic
- All Blade views use `__('general.*')` / `__('admin.*')` helpers — no hardcoded English strings

### CSS
- Always use CSS variables — **never** hardcode colors
- Storefront tokens: `--orange: #ea580c`, `--orange-dark: #c2410c`, `--orange-light: #fff7ed`, `--gray-900: #111827`
- Admin tokens: same orange + `--dark: #1a1a1a`, `--muted: #6b7280`, `--border: #e5e7eb`, `--bg: #fff8f4`, `--green: #16a34a`, `--red: #dc2626`, `--blue: #2563eb`, `--amber: #d97706`
- CSS scoping prefixes: `cp-` (create product), `gen3d-` (3D gen UI), `tryon-` (try-on UI)

### JavaScript Globals
Admin layout: `window.showToast(msg, type)`, `openDrawer(html, title)`, `closeDrawer()`, `confirmAction(title, body, cb)`, `submitForm(formEl, cb)`, `editProduct(id)`, `deleteProduct(id, name)`, `openOrderDrawer(orderId)`, `adminNavigate(url)`, `previewProduct(slug)`, `previewCmsPage(id)`

Storefront: `window.showToast(msg, type)`, `window.initViewer3D(containerId, modelUrl)`

---

## KEY MODEL REFERENCE

### Product
- `model3d_status`: plain string (not DB enum) — `idle | queued | processing | ready | failed`
- Helpers: `is3DReady()`, `is3DProcessing()` (true when queued OR processing), `is3DFailed()`, `get3DModelUrl()` (returns `placeholder.glb` if not ready)
- Traits: `SoftDeletes`

### Order
- Has two total columns: `total_amount` (legacy dashboard sums) and `grand_total` (refund/payment logic) — always use `grand_total` for payment/refund calculations
- `grandTotal()` recomputes from `total_amount - discount + tax + shipping_cost`
- Status values: `pending | paid | payment_blocked | refunded | partially_refunded | cancelled | completed | processing | shipped | delivered`
- Supports guest orders (`user_id` nullable; `guest_email`, `guest_name` fields)

### VirtualTryon
- Status values: `pending | processing_body | processing_fit | ready | failed`
- Body model reused from `User.body_model_path` when `User.hasReusableBodyModel()` is true
- `expires_at` managed by `CleanExpiredTryOns` artisan command (runs daily)

### User
- `role`: `'admin' | 'buyer'`
- Helpers: `isAdmin()`, `isBlocked()`, `hasReusableBodyModel()` (checks flag + file exists on disk), `getOrCreateCart()`

### Payment
- No `user_id` directly — ownership via `order → user`
- Status values: `pending | paid | failed | refunded`

---

## TESTS

```
tests/Feature/
  PaymentFlowTest.php        — payment methods access, ownership, retry blocking
  PaymentErrorMapperTest.php — gateway error code → user message mapping
  RefundServiceTest.php      — full/partial refund logic
  ShippingCalculatorTest.php — flat/per-unit/weight-based/free threshold
  TaxCalculatorTest.php      — percentage/scoped/compound/inclusive tax
```

**Missing:** No `TryOn` feature tests exist yet.

Test rules: use `RefreshDatabase`; mock all AI services; never call real APIs; assert JSON structure for AJAX endpoints; no factories yet — use direct `Model::create()`.

---

## COMMON COMMANDS

```bash
# First-time setup
composer install && cp .env.example .env
php artisan key:generate && php artisan migrate && php artisan db:seed
php artisan storage:link && npm install && npm run build
pip install -r scripts/requirements_tryon.txt

# Dev (all-in-one)
composer dev

# Queue worker (required for AI jobs)
php artisan queue:work --queue=default --tries=3 --timeout=600

# After pulling changes
git pull && composer install && npm install && php artisan migrate
php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear
npm run build

# Debug
php artisan route:list | grep {keyword}
php artisan queue:failed && php artisan queue:retry all
php artisan tinker
php artisan pail --timeout=0

# Test
php artisan test
```

Crontab: `* * * * * cd /path/to/voxura && php artisan schedule:run >> /dev/null 2>&1`

---

## GOTCHAS

1. **`Generate3DModelJob` timeout is 600, not 180.** Replicate inference for Hunyuan/Rodin takes 8–10 min.
2. **`TrellisService` calls `api.replicate.com`, not HuggingFace.** `HF_TRELLIS_SPACE` env var exists but is unused.
3. **Body provider is `fal`, not `gradio`.** `Sam3DBodyGradioService` does not exist; use `Sam3DBodyFalService`.
4. **`config/payment.php` default is `'paypal'` (disabled).** Always set `PAYMENT_GATEWAY=tap` in `.env`.
5. **`AdminController` is monolithic (~1100+ lines).** No standard resource split for admin routes.
6. **`model3d_status` is a plain string column, not a DB ENUM.** Use string literals for comparisons.
7. **No Eloquent factories exist yet.** Tests use direct `Model::create()` calls.
8. **`composer dev` is dev-only.** Uses `queue:listen --tries=1 --timeout=0` — use a proper process manager in production.
9. **Both `lang/en/general.php` and `lang/ar/general.php` exist** (352 lines each) and are comprehensive.
10. **Language-switch route IS wired.** `POST /language/switch` → `LanguageController@switch` is registered and working.

---

*Last updated: 2026-05-09 — generated by Claude Code scan of /home/islamdriadi/voxura*