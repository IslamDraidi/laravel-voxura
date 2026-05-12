# CLAUDE.md — Voxura Project Bible

> This file is the authoritative reference for AI assistants working on the Voxura codebase.
> Keep it updated when adding new features, services, or architectural changes.

---

## SECTION 1 — PROJECT OVERVIEW

| Key | Value |
|-----|-------|
| **Project Name** | Voxura |
| **Type** | Fashion & apparel e-commerce platform |
| **Framework** | Laravel 12.x |
| **PHP Version** | ^8.2 |
| **Team Size** | 4 developers |
| **Key Services** | Tap Payments, HuggingFace (QwenVL), Replicate (TRELLIS/HunyuanD/Rodin), fal.ai (SAM-3D body), Three.js (viewer3d.js), trimesh (Python garment fitting) |

### What This Project Does
Voxura is a fashion e-commerce platform with AI-powered features including virtual try-on (fitting garments onto a 3D body scan), 3D product model generation from product images via Replicate or HuggingFace, and full multilingual support (English + Arabic). Language switching is fully implemented via `POST /language/switch` and the `SetLocale` middleware.

---
  When creating git commits, do not add Co-Authored-By lines.
  
## SECTION 2 — REPOSITORY MAP

```
app/
  Http/
    Controllers/
      AdminController.php          — Monolithic admin controller: dashboard, products, orders,
                                     customers, shipping, tax, banners, email templates,
                                     reports, inventory, categories, refunds, CMS, etc.
      Admin/
        AdminRefundController.php  — Dedicated admin refund processing endpoint
      AdminPreviewController.php   — Enables/disables "view as customer" admin preview mode
      Auth/
        Login.php                  — Handles POST /login authentication
        Logout.php                 — Handles POST+GET /logout
        RegisterController.php     — Handles POST /register new user creation
      CartController.php           — Session/DB cart: add, update, remove, clear
      CategoryController.php       — Admin category CRUD (index, store, update, destroy)
      CmsPageController.php        — Admin + public CMS page management
      CompareController.php        — Session-based product comparison (no auth required)
      Controller.php               — Base controller
      CouponController.php         — Admin coupon management + storefront coupon apply
      FeedbackController.php       — Product reviews: store, destroy, markHelpful
      LikeController.php           — Wishlist toggle and index
      OrderController.php          — Checkout flow: checkout, place, calculateTotals; order history
      PaymentController.php        — Payment flow: showMethods, processPayment, callback,
                                     cancel, retry, failed, webhook
      ProductController.php        — Storefront product show and search
      ProfileController.php        — Profile edit: updateInfo, updatePassword
      TryOnController.php          — Virtual try-on: initiate (JSON), status (JSON),
                                     delete, history
      UserController.php           — Homepage (index)
    Middleware/
      AdminMiddleware.php          — Guards admin routes; requires role=admin
      AdminPreviewMode.php         — Injects preview banner; hides interactive storefront features
    Requests/
      ProductRequest.php           — Validation for storefront product interactions
      Admin/
        ShippingMethodRequest.php  — Validation for admin shipping method create/update
        ShippingZoneRequest.php    — Validation for admin shipping zone create/update
        TaxRateRequest.php         — Validation for admin tax rate create/update

  Models/
    Banner.php           — Hero/slider banners with sort_order and active toggle
    CartItem.php         — Line items in a ShoppingCart (with variant support)
    Category.php         — Product categories; supports parent_id for nested hierarchy
    CmsPage.php          — CMS pages (title, slug, content, status)
    Coupon.php           — Discount coupons with active flag
    EmailTemplate.php    — Editable transactional email templates (key, subject, body)
    Feedback.php         — Product reviews with helpful_votes counter
    Like.php             — Wishlist pivot: user_id + product_id
    Order.php            — Orders with multi-status enum, totals, refund helpers
    OrderItem.php        — Per-line items; tracks variant, price, quantity
    Payment.php          — Payment records; tracks gateway, status, failure details
    Product.php          — Core product model; SoftDeletes; 3D model status/path helpers
    ProductImage.php     — Multiple images per product with sort_order
    ProductVariant.php   — Size/color/etc. variants per product
    Refund.php           — Refund records linked to Order and Payment
    ShippingMethod.php   — Shipping methods (flat_rate, per_unit, weight_based, free)
    ShippingZone.php     — Geographic shipping zones with per-method rate overrides
    ShoppingCart.php     — One-per-user persistent cart
    TaxRate.php          — Tax rates: percentage, category-scoped, compound ordering
    User.php             — Authenticatable user; isAdmin(), isBlocked(), hasReusableBodyModel()
    VirtualTryon.php     — Try-on records: status flow, photo/body/result paths, consent

  Services/
    GuestCart.php               — Session-based cart for unauthenticated users
    GuestCartItem.php           — Value object representing a guest cart line item
    OrderTotalCalculator.php    — Assembles subtotal, tax, shipping, coupons into grand total
    ShippingCalculator.php      — Calculates shipping cost per method type and zone
    TaxCalculator.php           — Applies tax rates (with compound + inclusive support)
    AI/
      Contracts/
        TryOnBodyProvider.php   — Interface implemented by body-generation services
      GarmentFittingService.php — Runs scripts/fit_garment.py via Symfony Process to merge
                                   body GLB + product GLB into a result GLB
      Model3DPipeline.php       — Orchestrates QwenVL image selection → Trellis GLB generation
      QwenVLService.php         — Calls HuggingFace QwenVL to select best product image
                                   for 3D reconstruction; falls back to first image if no token
      Sam3DBodyFalService.php   — Calls fal.ai queue API to generate a body GLB from a photo;
                                   implements TryOnBodyProvider
      TrellisService.php        — Calls Replicate API (TRELLIS/HunyuanD/Rodin/TRELLIS2)
                                   to generate a product GLB; supports 4 provider backends
      TryOnPipeline.php         — Orchestrates body generation (or reuse) + garment fitting
                                   → notifies user via TryOnReadyNotification
    Payment/
      PaymentGatewayInterface.php — Interface for payment gateway implementations
      PaymentErrorMapper.php      — Maps gateway error codes to user-facing messages
      PayPalPaymentService.php    — PayPal integration (disabled via config/payment.php)
      TapPaymentService.php       — Tap Payments integration (ACTIVE)
      RefundService.php           — Processes full and partial refunds via gateway

  Jobs/
    Generate3DModelJob.php       — Queued job: tries=3, timeout=600, backoff=30;
                                    delegates to Model3DPipeline; updates status on failure
    ProcessVirtualTryOnJob.php   — Queued job: tries=2, timeout=300, backoff=60;
                                    delegates to TryOnPipeline; updates status on failure

  Notifications/
    AdminPaymentBlockedNotification.php  — Notifies admin when payment is blocked
    PaymentFailedNotification.php        — Notifies customer on payment failure
    RefundConfirmationNotification.php   — Notifies customer when refund is processed
    TryOnReadyNotification.php           — Notifies customer when virtual try-on result is ready

  Exceptions/
    Model3DGenerationException.php  — Thrown when 3D generation fails (extends Exception)
    Model3DTimeoutException.php     — Thrown when a 3D generation request times out

  Console/
    Commands/
      CleanExpiredTryOns.php       — Artisan command: tryon:cleanup; run daily via scheduler

config/
  app.php          — Standard Laravel app config
  auth.php         — Auth guards and providers
  cache.php        — Cache store config (database by default)
  database.php     — DB connection config
  filesystems.php  — Storage disk definitions
  logging.php      — Log channel config
  mail.php         — Mail transport config
  model3d.php      — AI 3D generation settings: providers (trellis/trellis2/hunyuan/rodin),
                     Replicate token/versions, virtual try-on sub-config (fal.ai endpoint,
                     Python script path, storage paths, timeouts)
  payment.php      — Payment gateway config: Tap (enabled=true), PayPal (enabled=false)
  queue.php        — Queue driver config (database)
  services.php     — Third-party service credentials
  session.php      — Session driver config (database)

database/
  migrations/
    0001_01_01_000000_create_users_table.php
    0001_01_01_000001_create_cache_table.php
    0001_01_01_000002_create_jobs_table.php
    2026_03_02_194327_create_personal_access_tokens_table.php
    2026_03_07_214127_create_categories_table.php
    2026_03_07_214212_create_products_table.php
    2026_03_07_214356_create_shopping_carts_table.php
    2026_03_07_214439_create_orders_table.php
    2026_03_07_214521_create_cart_items_table.php
    2026_03_07_214628_create_order_items_table.php
    2026_03_07_214726_create_payments_table.php
    2026_03_07_214814_create_feedbacks_table.php
    2026_03_18_205233_add_is_admin_to_users_table.php
    2026_03_19_005118_create_likes_table.php
    2026_04_02_211246_add_is_blocked_to_users_table.php
    2026_04_02_211247_create_coupons_table.php
    2026_04_02_211247_create_product_images_table.php
    2026_04_02_211249_add_coupon_fields_to_orders_table.php
    2026_04_03_000001_create_product_variants_table.php
    2026_04_03_000002_add_variant_id_to_cart_items_table.php
    2026_04_03_000003_add_variant_fields_to_order_items_table.php
    2026_04_03_100000_create_banners_table.php
    2026_04_03_144106_create_cms_pages_table.php
    2026_04_03_200000_create_tax_rates_table.php
    2026_04_03_200001_add_tax_amount_to_orders_table.php
    2026_04_03_210000_create_shipping_methods_table.php
    2026_04_03_210001_add_shipping_fields_to_orders_table.php
    2026_04_03_300000_create_email_templates_table.php
    2026_04_04_170458_add_merchandising_fields_to_products_table.php
    2026_04_04_170459_add_helpful_votes_to_feedbacks_table.php
    2026_04_04_222636_add_role_to_users_table.php
    2026_04_04_300000_extend_shipping_methods_table.php
    2026_04_04_300001_create_shipping_zones_table.php
    2026_04_04_300002_create_shipping_zone_method_table.php
    2026_04_04_300003_extend_tax_rates_table.php
    2026_04_04_300004_extend_orders_table.php
    2026_04_05_000001_extend_orders_status_enum.php
    2026_04_05_000002_add_failure_tracking_to_payments_table.php
    2026_04_05_000003_create_refunds_table.php
    2026_04_05_000010_add_parent_id_to_categories_table.php
    2026_04_06_000001_add_has_colors_to_products_table.php
    2026_04_11_110020_add_model3d_to_products_table.php
    2026_04_20_000001_add_3d_generation_status_to_products_table.php
    2026_04_30_000001_create_virtual_tryons_table.php
    2026_04_30_000002_add_tryon_fields_to_users_table.php
    2026_05_01_000001_add_guest_fields_to_orders_table.php
  seeders/
    DatabaseSeeder.php               — Seeds CMS pages, admin user, categories, sample products
    PaymentEmailTemplateSeeder.php   — Seeds payment-related email templates (payment_failed, etc.)

resources/views/
  components/
    layout.blade.php          — Storefront layout: nav, hero mega-dropdown, cart badge,
                                 compare bar, footer, Alpine.js, window.showToast
    admin-layout.blade.php    — Admin layout: sidebar nav, topbar, sub-nav tabs, drawer,
                                 confirm modal, preview modal, AJAX navigation,
                                 window.showToast, window.openDrawer, window.submitForm
    about.blade.php           — About section component
    contact.blade.php         — Contact section component
    featured-product.blade.php — Featured product card component
    hero.blade.php             — Hero/banner slider component
    product-card.blade.php     — Product card used in grids
    product-grid.blade.php     — Grid container for product cards
  home.blade.php              — Homepage view
  welcome.blade.php           — Landing/welcome view
  admin/
    dashboard.blade.php                — Admin dashboard with stats and charts
    archive.blade.php                  — Soft-deleted products archive
    banners/index.blade.php            — Banner management
    catalog/attribute-families.blade.php — Placeholder attribute families tab
    catalog/attributes.blade.php       — Placeholder attributes tab
    categories/index.blade.php         — Category CRUD
    cms/pages/create.blade.php         — CMS page create form
    cms/pages/edit.blade.php           — CMS page edit form
    cms/pages/index.blade.php          — CMS pages list
    coupons/index.blade.php            — Coupon management
    customers/gdpr.blade.php           — GDPR data requests (placeholder)
    customers/groups.blade.php         — Customer groups (placeholder)
    customers/index.blade.php          — Customer list with block/unblock
    customers/reviews.blade.php        — Customer reviews moderation
    email-templates/index.blade.php    — Email template editor
    inventory/index.blade.php          — Inventory bulk stock update
    marketing/communications.blade.php — Marketing communications (placeholder)
    marketing/seo.blade.php            — SEO settings (placeholder)
    orders/index.blade.php             — Orders list with status filter
    orders/show.blade.php              — Order detail view with refund
    products/create.blade.php          — Admin product create form (with 3D gen trigger)
    products/edit.blade.php            — Admin product edit form
    products/index.blade.php           — Admin product list with AJAX inline editing
    reports/customers.blade.php        — Customer reports
    reports/index.blade.php            — Sales reports with chart
    reports/products.blade.php         — Product reports
    sales/invoices.blade.php           — Invoices (placeholder)
    sales/refunds.blade.php            — Refunds list
    sales/rma.blade.php                — RMA (placeholder)
    sales/shipments.blade.php          — Shipments (placeholder)
    sales/transactions.blade.php       — Transactions list
    shipping/create.blade.php          — Create shipping method
    shipping/edit.blade.php            — Edit shipping method
    shipping/index.blade.php           — Shipping methods list
    shipping/zones/_form.blade.php     — Shipping zone form partial
    shipping/zones/create.blade.php    — Create shipping zone
    shipping/zones/edit.blade.php      — Edit shipping zone
    shipping/zones/index.blade.php     — Shipping zones list
    tax/_form.blade.php                — Tax rate form partial
    tax/create.blade.php               — Create tax rate
    tax/edit.blade.php                 — Edit tax rate
    tax/index.blade.php                — Tax rates list
  auth/
    login.blade.php    — Login form
    register.blade.php — Registration form
  cart/
    index.blade.php    — Cart view
  checkout/
    index.blade.php    — Checkout form with totals calculator
  compare/
    index.blade.php    — Product comparison table (session-based)
  orders/
    index.blade.php    — Customer order history
    show.blade.php     — Customer order detail
  pages/
    (rendered dynamically via CmsPageController)
  payment/
    failed.blade.php   — Payment failure page
    methods.blade.php  — Payment method selection (Tap active, PayPal disabled)
  products/
    show.blade.php     — Product detail page with 3D viewer and try-on modal
  profile/
    edit.blade.php     — Profile info + password update
    tryons.blade.php   — Virtual try-on history for the authenticated user
  search/
    (search results rendered by ProductController)
  wishlist/
    (wishlist rendered by LikeController)

public/
  js/
    viewer3d.js       — Three.js-based GLB viewer; exposes window.initViewer3D(containerId, modelUrl)
  images/             — Product images served directly (NOT via Storage)
  (favicon, apple-touch-icon, placeholder.glb)

scripts/
  fit_garment.py           — Python script: merges body GLB + product GLB using trimesh;
                              accepts 4 CLI args: body_path, product_path, output_path, category
  requirements_tryon.txt   — trimesh>=4.0.0, numpy>=1.24.0, pyglet>=2.0.0

lang/
  en/general.php    — English UI translations (352 lines; navigation, auth, product, cart, checkout, orders, profile, try-on, etc.)
  en/admin.php      — English admin panel translations
  en/tax.php        — English tax-related translations
  en/shipping.php   — English shipping-related translations
  ar/general.php    — Arabic UI translations (mirrors en/general.php)
  ar/admin.php      — Arabic admin panel translations
  ar/tax.php        — Arabic tax-related translations
  ar/shipping.php   — Arabic shipping-related translations
```

---

## SECTION 3 — ARCHITECTURE RULES

### Database Rules
- All seeders use `firstOrCreate()` or `updateOrCreate()` — never plain `create()` in seed logic
- Images stored in `public/images/` — NOT `storage/`
- Product images relation is `images()` — NOT `productImages()`
- Soft deletes used on Product model
- Queue driver: `database`
- Session driver: `database`
- Cache store: `database`
- Scheduler registered in `bootstrap/app.php` (Laravel 11/12 style — no `Console/Kernel.php`)

### Storage Paths
| Content | Path |
|---------|------|
| Product 3D models | `storage/app/public/models/{product_id}/{filename}.glb` |
| Body models | `storage/app/public/bodies/{user_id}/body_{...}.glb` |
| Try-on results | `storage/app/public/tryon_results/{user_id}/tryon_{id}.glb` |
| Product images | `public/images/{filename}` |
| User try-on photos | `storage/app/public/tryons/{user_id}/` |

### Queue Jobs Rules
- `Generate3DModelJob`: `tries=3`, `timeout=600`, `backoff=30`
- `ProcessVirtualTryOnJob`: `tries=2`, `timeout=300`, `backoff=60`
- Always `refresh()` the model at the start of `handle()` to avoid stale data
- Check status before processing (`isReady()`) to avoid duplicates
- Job failure **must** update model status to `'failed'`
- Product save must **never** fail because of 3D generation
- Discard silently on `ModelNotFoundException` (product/tryon deleted before job ran)

### Controllers Rules
- Return `response()->json()` for all routes called from JS (TryOnController, etc.)
- Always keep `redirect()` as fallback for non-AJAX form flows
- Verify ownership before exposing user-specific resources (e.g., `$tryon->user_id === auth()->id()`)
- Use `abort(403)` for unauthorized access
- Admin controller is a single monolithic class (`AdminController`) — do not split unless explicitly asked

### Blade Layouts
- Storefront: `<x-layout title="...">`  (optionally `mainClass="full-width"` for edge-to-edge)
- Admin: `<x-admin-layout title="..." section="..." active="...">`
- **Never** use `@extends` in any view file

### AI Services Rules
- Never call AI APIs directly from controllers
- All AI calls go through Service classes in `app/Services/AI/`
- 3D model generation uses Replicate API (not HuggingFace directly for TRELLIS); QwenVL still uses HuggingFace
- The `MODEL3D_PROVIDER` env var selects the backend: `trellis` | `trellis2` | `hunyuan` | `rodin`
- Body generation uses fal.ai queue API (`TRYON_BODY_PROVIDER=fal`)
- Never expose `HF_API_TOKEN`, `FAL_KEY`, or `REPLICATE_API_TOKEN` in frontend
- Always implement retry with exponential backoff inside service classes
- All AI service exceptions must be caught in Job `failed()` methods

### Payment Rules
- Tap Payments is the only active gateway (`config/payment.php` → `tap.enabled = true`)
- PayPal exists in codebase but is disabled (`paypal.enabled = false`)
- To enable PayPal: set `'paypal' => ['enabled' => true]` in `config/payment.php`
- `config/payment.php` has `'default' => env('PAYMENT_GATEWAY', 'paypal')` — this default is misleading since PayPal is disabled; the active gateway is Tap
- Never expose payment credentials in Blade or JS
- Webhooks are CSRF-exempt: `webhooks/payment/*` is excluded in `bootstrap/app.php`

### Security Rules
- All sensitive keys in `.env` only
- CSRF token required in all AJAX POST requests (meta tag `csrf-token` is present in both layouts)
- User photos stored per `user_id` directory — never cross-user access
- Photo consent must be explicit before saving body model to user profile
- Body model is only saved to user record if `photo_consent = true`

---

## SECTION 4 — NAMING CONVENTIONS

### Models
```php
// Singular PascalCase
User, Product, VirtualTryon

// hasMany relationships — camelCase plural
public function orders(): HasMany { ... }
public function images(): HasMany { ... }   // NOT productImages()

// belongsTo relationships — camelCase singular
public function user(): BelongsTo { ... }
public function product(): BelongsTo { ... }

// Status helpers on Product
public function is3DReady(): bool { ... }
public function is3DProcessing(): bool { ... }
public function is3DFailed(): bool { ... }
public function get3DModelUrl(): string { ... }

// Status helpers on VirtualTryon
public function isReady(): bool { ... }
public function isProcessing(): bool { ... }
public function isFailed(): bool { ... }
```

### Controllers
```php
// Resource controllers
ProductController, OrderController, CategoryController, CouponController

// Admin — monolithic
AdminController       // handles all admin sections
Admin/AdminRefundController  // dedicated refund endpoint

// AJAX response format
return response()->json(['success' => true, 'data' => $data]);
return response()->json(['success' => false, 'message' => '...'], 422);
```

### Services
```php
// Location: app/Services/AI/
// Naming: {Technology}{Purpose}Service.php
Sam3DBodyFalService      // SAM-3D body via fal.ai
TrellisService           // TRELLIS (and Replicate alternatives) for product GLB
QwenVLService            // QwenVL image selection via HuggingFace
GarmentFittingService    // Python subprocess garment fitting

// Pipelines (orchestrators)
Model3DPipeline          // QwenVL → Trellis → save
TryOnPipeline            // body generation (or reuse) → garment fitting → notify

// Main methods
$service->selectBestImage($imagePaths, $productName);
$service->generateModel($imagePath, $description, $productId);
$service->generateBodyModel($photoAbsPath, $heightCm, $userId);
$service->fitGarment($bodyAbsPath, $productAbsPath, $outputAbsPath, $category);
$pipeline->run($product);
$pipeline->run($tryon);
```

### Jobs
```php
// Naming: {Verb}{Subject}Job.php
Generate3DModelJob, ProcessVirtualTryOnJob

// Always implement
public function handle(): void { ... }
public function failed(\Throwable $e): void { ... }

// Properties
public int $tries = 3;      // Generate3DModelJob
public int $timeout = 600;  // Generate3DModelJob (NOTE: 600, not 180 as template assumed)
public int $backoff = 30;

public int $tries = 2;      // ProcessVirtualTryOnJob
public int $timeout = 300;  // ProcessVirtualTryOnJob
public int $backoff = 60;
```

### Migrations
```
YYYY_MM_DD_HHMMSS_create_{table}_table.php
YYYY_MM_DD_HHMMSS_add_{columns}_to_{table}_table.php
YYYY_MM_DD_HHMMSS_extend_{table}_table.php
```

### Blade Views
```
resources/views/admin/{section}/{action}.blade.php
resources/views/{section}/{action}.blade.php
resources/views/{section}/_partial.blade.php   ← partials prefixed with _
```

CSS scoping prefixes:
- `cp-` → create product
- `gen3d-` → 3D generation UI
- `tryon-` → virtual try-on UI

### JavaScript
```js
// Admin layout global functions
window.showToast(message, type)        // 'success' | 'error' | 'warning'
window.openDrawer(html, title)
window.closeDrawer()
window.confirmAction(title, body, cb)
window.submitForm(formEl, successCb)   // AJAX form helper with inline error display
window.editProduct(productId)          // AJAX inline product editing
window.deleteProduct(productId, name)  // AJAX soft-delete with confirm
window.openOrderDrawer(orderId)        // AJAX order detail drawer
window.adminNavigate(url)              // AJAX sidebar navigation with history.pushState
window.previewProduct(slug)            // Product preview in iframe modal
window.previewCmsPage(id)              // CMS page preview in iframe modal

// Storefront global functions
window.showToast(message, type)        // Also defined in layout.blade.php
window.initViewer3D(containerId, modelUrl)  // Three.js GLB viewer (public/js/viewer3d.js)
```

### CSS Design Tokens
```css
/* Storefront layout.blade.php */
--orange:       #ea580c
--orange-dark:  #c2410c
--orange-light: #fff7ed
--gray-900:     #111827
--white:        #ffffff

/* Admin admin-layout.blade.php */
--orange:       #ea580c
--orange-dark:  #c2410c
--orange-pale:  #fff7ed
--dark:         #1a1a1a
--muted:        #6b7280
--border:       #e5e7eb
--bg:           #fff8f4
--white:        #fff
--green:        #16a34a
--red:          #dc2626
--blue:         #2563eb
--amber:        #d97706
```
- Always use CSS variables — **never** hardcode colors
- RTL support fully implemented: `dir` and `lang` attributes on `<html>` set dynamically; RTL CSS rules in both layouts; Tajawal font loaded for Arabic

### Translations
```php
// Supported locales: en, ar (config/app.php: 'available_locales' => ['en', 'ar'])
// Language switch: POST /language/switch → LanguageController@switch
// Middleware: App\Http\Middleware\SetLocale (registered globally in bootstrap/app.php)
// Guest locale: stored in session('locale')
// User locale: stored in users.preferred_locale (migration: 2026_05_09_000001_add_locale_to_users_table)
//
// lang/en/general.php — English UI translations (352 lines)
// lang/ar/general.php — Arabic UI translations
// lang/en/admin.php   — English admin translations
// lang/ar/admin.php   — Arabic admin translations
// lang/en/tax.php    — English tax labels
// lang/ar/tax.php    — Arabic tax labels
// lang/en/shipping.php — English shipping labels
// lang/ar/shipping.php — Arabic shipping labels

// NOTE: lang/en/general.php and lang/ar/general.php exist and are comprehensive.
// All Blade views use __() helpers — no hardcoded English strings remain.
```

---

## SECTION 5 — KEY DATA MODELS

### Product
```
Fields:
  name, slug, description, price, stock, image, category_id
  sale_badge, is_new, max_order_quantity, stock_alert_threshold
  delivery_estimate, material, fit, care_instructions, sku
  shipping_returns, color_swatches (json), has_colors (bool)
  size_guide (json)
  has_3d_model (bool), model3d_status (string), model3d_path
  model3d_queued_at, model3d_generated_at, model3d_error
  model3d_selected_image, model3d_job_id

model3d_status values (not a DB enum — stored as string):
  idle | queued | processing | ready | failed

Relationships:
  category()    → BelongsTo Category
  images()      → HasMany ProductImage (ordered by sort_order)
  variants()    → HasMany ProductVariant (ordered by type, value)
  feedbacks()   → HasMany Feedback

Helpers:
  is3DReady()        → bool
  is3DProcessing()   → bool (true when queued OR processing)
  is3DFailed()       → bool
  get3DModelUrl()    → string (returns placeholder.glb if not ready)

Traits: SoftDeletes
```

### Order
```
Fields:
  user_id (nullable — supports guest orders), coupon_id,
  shipping_method_id, shipping_zone_id,
  total_amount, discount_amount, tax_amount, shipping_cost,
  tax_breakdown (json), shipping_tax_amount, subtotal, grand_total,
  currency, channel, coupon_code, status,
  shipping_address (json), guest_email, guest_name

status values:
  pending | paid | payment_blocked | refunded |
  partially_refunded | cancelled | completed | processing | shipped | delivered

NOTE: Order uses both total_amount (legacy dashboard sums) and grand_total (refund logic).
      Always use grand_total for payment/refund calculations.

Relationships:
  user()           → BelongsTo User (nullable for guest orders)
  coupon()         → BelongsTo Coupon
  items()          → HasMany OrderItem
  payments()       → HasMany Payment
  refunds()        → HasMany Refund
  shippingMethod() → BelongsTo ShippingMethod
  shippingZone()   → BelongsTo ShippingZone

Helpers:
  recipientEmail()        → ?string
  recipientName()         → string
  totalRefunded()         → float
  refundableAmount()      → float
  isFullyRefunded()       → bool
  isPartiallyRefunded()   → bool
  grandTotal()            → float (recomputes from total_amount - discount + tax + shipping)
  statusColor()           → string (hex for display)
  statusBg()              → string (hex background for badges)
```

### VirtualTryon
```
Fields:
  user_id, product_id, photo_path, body_model_path,
  result_model_path, status, height_cm, error_message,
  photo_consent (bool), queued_at, body_generated_at,
  result_generated_at, expires_at

status values:
  pending | processing_body | processing_fit | ready | failed

Relationships:
  user()    → BelongsTo User
  product() → BelongsTo Product

Helpers:
  isReady()       → bool
  isProcessing()  → bool (true for pending | processing_body | processing_fit)
  isFailed()      → bool
  getResultUrl()  → ?string (Storage::disk('public')->url)
  getBodyUrl()    → ?string

Notes:
  - expires_at: set externally (CleanExpiredTryOns command handles cleanup)
  - Body model reused from User.body_model_path when User.hasReusableBodyModel() = true
  - Body saved to user record only when photo_consent = true
```

### Payment
```
Fields:
  order_id, amount, status, payment_method, transaction_id,
  failure_reason, failure_code, attempts, last_attempted_at,
  gateway (string), gateway_response (json)

status values: pending | paid | failed | refunded

NOTE: Payment does not have a user_id field directly;
      ownership is via order → user.

Relationships:
  order()   → BelongsTo Order
  refunds() → HasMany Refund
```

### User
```
Fields (beyond Authenticatable defaults):
  role (string: 'admin' | 'buyer'), is_blocked (bool),
  has_body_model (bool), body_model_path, body_model_generated_at, body_height_cm

Helpers:
  isAdmin()              → bool (role === 'admin')
  isBlocked()            → bool
  isBuyer()              → bool
  cartCount()            → int
  wishlistCount()        → int
  hasReusableBodyModel() → bool (checks has_body_model + file exists on disk)
  getOrCreateCart()      → ShoppingCart

Relationships:
  cart()          → HasOne ShoppingCart
  likes()         → HasMany Like
  orders()        → HasMany Order
  likedProducts() → BelongsToMany Product (via likes)
  tryons()        → HasMany VirtualTryon
```

---

## SECTION 6 — TEST EXPECTATIONS

### Existing Tests
```
tests/
  Feature/
    ExampleTest.php               — Default Laravel example test
    PaymentFlowTest.php           — Tests payment methods page access, ownership, retry blocking
    PaymentErrorMapperTest.php    — Tests gateway error code → user message mapping
    RefundServiceTest.php         — Tests full/partial refund logic and validation
    ShippingCalculatorTest.php    — Tests flat/per-unit/weight-based/free threshold calculations
    TaxCalculatorTest.php         — Tests percentage/category-scoped/compound/inclusive tax
  Unit/
    ExampleTest.php               — Default Laravel unit example test
  Pest.php                        — Pest configuration
  TestCase.php                    — Base test case
```

Note: No `tests/Feature/TryOn/` directory was found. TryOn feature tests are absent from the codebase.

### Expected Test Coverage (not yet written)
```
tests/Feature/TryOn/
  TryOnInitiateTest.php
    ✓ Authenticated user can initiate
    ✓ Guest cannot initiate
    ✓ Product without 3D model rejected
    ✓ Photo required validation
    ✓ Duplicate pending rejected (returns existing tryon_id)

  TryOnPipelineTest.php
    ✓ Reuses existing body model when user.hasReusableBodyModel()
    ✓ Saves body model to user when photo_consent = true
    ✓ Does NOT save body model when photo_consent = false
    ✓ Fails gracefully when SAM3D/fal.ai unavailable
```

### Test Rules
- Use `RefreshDatabase` trait in all feature tests
- Mock AI services — **never** call real APIs in tests
- Use factories or direct `Model::create()` for model creation (factories not yet generated)
- Assert JSON structure for all AJAX endpoints
- Test both authenticated and guest states

---

## SECTION 7 — ENVIRONMENT VARIABLES

```env
# ── App ──────────────────────────────────────────────
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

# ── Database ─────────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voxura
DB_USERNAME=root
DB_PASSWORD=

# ── Queue / Session / Cache ───────────────────────────
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database

# ── Payment: Tap Payments (ACTIVE) ───────────────────
TAP_SECRET_KEY=sk_test_...
TAP_PUBLIC_KEY=pk_test_...
# TAP_BASE_URL defaults to https://api.tap.company/v2
TAP_WEBHOOK_SECRET=

# ── Payment: PayPal (DISABLED — set enabled=true in config/payment.php to enable) ─
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_MODE=sandbox
PAYPAL_WEBHOOK_ID=

# ── Payment gateway default ──────────────────────────
# NOTE: config/payment.php default key reads this, but it defaults to 'paypal' which is disabled.
# The active gateway is always 'tap' regardless of this env var.
PAYMENT_GATEWAY=tap

# ── AI: 3D Model Generation (Replicate) ──────────────
REPLICATE_API_TOKEN=
MODEL3D_PROVIDER=hunyuan     # trellis | trellis2 | hunyuan | rodin
MODEL3D_GENERATION_ENABLED=true
MODEL3D_MAX_RETRIES=3
MODEL3D_TIMEOUT=300

# Optional Replicate version overrides (defaults are pinned in config/model3d.php)
REPLICATE_TRELLIS_MODEL=firtoz/trellis
REPLICATE_TRELLIS_VERSION=
REPLICATE_HUNYUAN_VERSION=
REPLICATE_RODIN_VERSION=
REPLICATE_TRELLIS2_VERSION=

# ── AI: Image Selection (HuggingFace QwenVL) ─────────
HF_API_TOKEN=your_huggingface_token_here
HF_QWEN3_VL_SPACE=Qwen/Qwen2.5-VL-7B-Instruct
HF_TRELLIS_SPACE=trellis-community/TRELLIS

# ── AI: Virtual Try-On (fal.ai) ──────────────────────
TRYON_ENABLED=true
TRYON_BODY_PROVIDER=fal
FAL_KEY=
FAL_SAM3D_ENDPOINT=fal-ai/sam-3/3d-body
PYTHON_PATH=/usr/bin/python3
TRYON_FIT_TIMEOUT=90
TRYON_MAX_RETRIES=3
TRYON_REQUEST_TIMEOUT=300

# ── Localization ─────────────────────────────────────
# Arabic translations exist in lang/ar/ but no language-switch route is wired yet
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

---

## SECTION 8 — COMMON COMMANDS

### First-Time Setup
```bash
git clone {repo}
cd voxura
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
npm install
npm run build
pip install -r scripts/requirements_tryon.txt
```

### Start Development
```bash
# All-in-one (web server + queue + logs + Vite)
composer dev

# Or individually:
php artisan serve

# Queue worker (required for AI jobs)
php artisan queue:work --queue=default --tries=3 --timeout=600

# Log tailing
php artisan pail
```

### Scheduler (add to crontab)
```cron
* * * * * cd /path/to/voxura && php artisan schedule:run >> /dev/null 2>&1
```
The scheduler runs `php artisan tryon:cleanup` daily (cleans expired try-on records).

### After Pulling Changes
```bash
git pull
composer install
npm install
php artisan migrate
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
npm run build
```

### Debugging
```bash
# Inspect routes
php artisan route:list | grep {keyword}

# Queue management
php artisan queue:failed
php artisan queue:retry all
php artisan queue:work --tries=1 --timeout=0  # dev: no retry, no timeout

# Tinker
php artisan tinker

# Log tailing (native)
php artisan pail --timeout=0

# Or classic
tail -f storage/logs/laravel.log
```

### Testing
```bash
composer test
# or
php artisan test
# or (Pest directly)
./vendor/bin/pest
```

---

## SECTION 9 — GOTCHAS & KNOWN ISSUES

### Discrepancies from Template Assumptions

1. **Generate3DModelJob timeout is 600, not 180.**
   The template assumed `timeout=180`. Actual code: `public int $timeout = 600;`. This is because Replicate inference for Hunyuan/Rodin can take 8–10 minutes.

2. **3D model generation uses Replicate API, not HuggingFace directly.**
   The template listed `HF_TRELLIS_SPACE` as the active backend. In practice, `TrellisService` calls `api.replicate.com/v1/predictions`. HuggingFace is only used for `QwenVLService` (image selection). The `HF_TRELLIS_SPACE` env var exists but is unused by the current service code.

3. **Body model provider is `fal` not `gradio`.**
   Template mentioned `TRYON_BODY_PROVIDER=gradio`. The actual default (and only implementation) is `fal`. There is no Gradio-based body provider class in the codebase.

4. **`lang/en/general.php` and `lang/ar/general.php` DO exist (352 lines each).**
   Contrary to older notes, both files exist and cover all UI strings. `lang/en/admin.php` and `lang/ar/admin.php` also exist. All Blade views use `__('general.*')` and `__('admin.*')` helpers — no hardcoded English strings remain.

5. **Language-switch route IS wired.**
   `POST /language/switch` → `LanguageController@switch` is registered. `SetLocale` middleware reads `user->preferred_locale` (or `session('locale')`) on every request. The JS functions `switchLang()` (storefront) and `switchLangAdmin()` (admin) submit a form POST to this route. RTL is fully applied via dynamic `dir` attribute on `<html>`.

6. **`config/payment.php` default gateway is `'paypal'` (disabled).**
   The `PAYMENT_GATEWAY` env var defaults to `'paypal'` in the config, but PayPal has `'enabled' => false`. The storefront correctly shows only Tap Payments. Always set `PAYMENT_GATEWAY=tap` in `.env` to avoid confusion.

7. **Order model has two "total" columns: `total_amount` and `grand_total`.**
   `total_amount` is used for legacy dashboard aggregation. `grand_total` is used in refund calculations and payment processing. The `grandTotal()` helper method recomputes dynamically from `total_amount - discount + tax + shipping_cost`.

8. **`AdminController` is a single monolithic class (~1100+ lines) handling all admin sections.**
   There is only one dedicated sub-controller: `Admin/AdminRefundController`. Do not assume a standard resource controller split exists for admin routes.

9. **Product `model3d_status` is a plain string column, not a database ENUM.**
   Valid values: `idle`, `queued`, `processing`, `ready`, `failed`. There is no Laravel enum cast — comparisons are done with string literals.

10. **`DatabaseSeeder` uses `updateOrCreate()` for products (not `firstOrCreate()`).**
    Products use `updateOrCreate` to ensure seed data stays current. Users and categories use `firstOrCreate`. This is intentional.

11. **No Eloquent model factories exist yet.**
    Feature tests create models via direct `User::create()` and `Order::create()` calls. Factories should be added to `database/factories/` before writing further tests.

12. **`Sam3DBodyGradioService` mentioned in template does not exist.**
    The actual class is `Sam3DBodyFalService` (fal.ai queue API, not Gradio HTTP polling).

13. **`composer dev` starts everything in one command (server + queue + logs + Vite).**
    The `composer dev` script uses `concurrently` and includes `php artisan queue:listen --tries=1 --timeout=0` — ideal for development but not for production. In production use a proper queue worker process manager.

---

*Last updated: 2026-05-09*
*Generated by: Claude Code — scan of /home/islamdriadi/voxura*
