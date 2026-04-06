<?php

use App\Http\Controllers\Admin\AdminRefundController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPreviewController;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CmsPageController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);
Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Public CMS pages
Route::get('/pages/{slug}', [CmsPageController::class, 'show'])->name('pages.show');

// Product compare (no auth required)
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/{product}', [CompareController::class, 'add'])->name('compare.add');
Route::delete('/compare/{product}', [CompareController::class, 'remove'])->name('compare.remove');
Route::delete('/compare', [CompareController::class, 'clear'])->name('compare.clear');
Route::post('/reviews/{feedback}/helpful', [FeedbackController::class, 'markHelpful'])->name('reviews.helpful');

Route::view('/register', 'auth.register')->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');
Route::post('/logout', Logout::class)->middleware('auth')->name('logout');
Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [Login::class, 'store'])->middleware('guest');

Route::middleware('auth')->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/items/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/items/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Wishlist
    Route::get('/wishlist', [LikeController::class, 'index'])->name('wishlist.index');
    Route::post('/likes/{product}/toggle', [LikeController::class, 'toggle'])->name('likes.toggle');

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'place'])->name('checkout.place');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.info');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Reviews
    Route::post('/products/{product}/reviews', [FeedbackController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{feedback}', [FeedbackController::class, 'destroy'])->name('reviews.destroy');

    // Coupon apply (AJAX)
    Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');

    // Checkout AJAX — calculate totals on shipping method change
    Route::post('/checkout/calculate', [OrderController::class, 'calculateTotals'])->name('checkout.calculate');

    // Payment flow
    Route::get('/payment/{order}', [PaymentController::class, 'showMethods'])->name('payment.methods');
    Route::post('/payment/{order}', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/{order}/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/{order}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::get('/payment/{order}/retry', [PaymentController::class, 'retry'])->name('payment.retry')->middleware('throttle:10,1');
    Route::get('/payment/{order}/failed', [PaymentController::class, 'failed'])->name('payment.failed');
});

// Payment gateway webhooks (no auth, CSRF exempted in bootstrap/app.php)
Route::post('/webhooks/payment/{gateway}', [PaymentController::class, 'webhook'])->name('payment.webhook');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products.index');
    Route::get('/products/create', [AdminController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroy'])->name('admin.products.delete');
    Route::get('/archive', [AdminController::class, 'archive'])->name('admin.archive');
    Route::post('/products/{product}/variants', [AdminController::class, 'addVariant'])->name('admin.variants.store');
    Route::delete('/products/{product}/variants/{variant}', [AdminController::class, 'removeVariant'])->name('admin.variants.destroy');
    Route::post('/products/{id}/restore', [AdminController::class, 'restore'])->name('admin.restore');
    Route::delete('/products/{id}/force-delete', [AdminController::class, 'forceDelete'])->name('admin.forceDelete');
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::post('/orders/{order}/refund', [AdminRefundController::class, 'processRefund'])->name('admin.orders.refund');

    // Customer management
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers.index');
    Route::post('/customers/{user}/block', [AdminController::class, 'blockCustomer'])->name('admin.customers.block');
    Route::post('/customers/{user}/unblock', [AdminController::class, 'unblockCustomer'])->name('admin.customers.unblock');

    // Coupon management
    Route::get('/coupons', [CouponController::class, 'index'])->name('admin.coupons.index');
    Route::post('/coupons', [CouponController::class, 'store'])->name('admin.coupons.store');
    Route::post('/coupons/{coupon}/toggle', [CouponController::class, 'toggleActive'])->name('admin.coupons.toggle');
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('admin.coupons.destroy');

    // Sales Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports.index');

    // Inventory Management
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('admin.inventory.index');
    Route::post('/inventory/stock', [AdminController::class, 'bulkStockUpdate'])->name('admin.inventory.stock');

    // Tax Rate Management
    Route::get('/tax', [AdminController::class, 'taxRates'])->name('admin.tax.index');
    Route::get('/tax/create', [AdminController::class, 'createTaxRate'])->name('admin.tax.create');
    Route::post('/tax', [AdminController::class, 'storeTaxRate'])->name('admin.tax.store');
    Route::get('/tax/{rate}/edit', [AdminController::class, 'editTaxRate'])->name('admin.tax.edit');
    Route::put('/tax/{rate}', [AdminController::class, 'updateTaxRate'])->name('admin.tax.update');
    Route::post('/tax/{rate}/toggle', [AdminController::class, 'toggleTaxRate'])->name('admin.tax.toggle');
    Route::delete('/tax/{rate}', [AdminController::class, 'destroyTaxRate'])->name('admin.tax.destroy');

    // Shipping Method Management
    Route::get('/shipping', fn () => redirect('/admin/shipping/methods'));
    Route::get('/shipping/methods', [AdminController::class, 'shippingMethods'])->name('admin.shipping.index');
    Route::get('/shipping/methods/create', [AdminController::class, 'createShippingMethod'])->name('admin.shipping.create');
    Route::post('/shipping/methods', [AdminController::class, 'storeShippingMethod'])->name('admin.shipping.store');
    Route::get('/shipping/methods/{method}/edit', [AdminController::class, 'editShippingMethod'])->name('admin.shipping.edit');
    Route::put('/shipping/methods/{method}', [AdminController::class, 'updateShippingMethod'])->name('admin.shipping.update');
    Route::post('/shipping/methods/{method}/toggle', [AdminController::class, 'toggleShippingMethod'])->name('admin.shipping.toggle');
    Route::delete('/shipping/methods/{method}', [AdminController::class, 'destroyShippingMethod'])->name('admin.shipping.destroy');

    // Shipping Zone Management
    Route::get('/shipping/zones', [AdminController::class, 'shippingZones'])->name('admin.zones.index');
    Route::get('/shipping/zones/create', [AdminController::class, 'createShippingZone'])->name('admin.zones.create');
    Route::post('/shipping/zones', [AdminController::class, 'storeShippingZone'])->name('admin.zones.store');
    Route::get('/shipping/zones/{zone}/edit', [AdminController::class, 'editShippingZone'])->name('admin.zones.edit');
    Route::put('/shipping/zones/{zone}', [AdminController::class, 'updateShippingZone'])->name('admin.zones.update');
    Route::post('/shipping/zones/{zone}/toggle', [AdminController::class, 'toggleShippingZone'])->name('admin.zones.toggle');
    Route::delete('/shipping/zones/{zone}', [AdminController::class, 'destroyShippingZone'])->name('admin.zones.destroy');

    // Banner / Slider Management
    Route::get('/banners', [AdminController::class, 'banners'])->name('admin.banners.index');
    Route::post('/banners', [AdminController::class, 'storeBanner'])->name('admin.banners.store');
    Route::post('/banners/{banner}/toggle', [AdminController::class, 'toggleBanner'])->name('admin.banners.toggle');
    Route::delete('/banners/{banner}', [AdminController::class, 'destroyBanner'])->name('admin.banners.destroy');
    Route::post('/banners/reorder', [AdminController::class, 'reorderBanners'])->name('admin.banners.reorder');

    // Email Template Management
    Route::get('/email-templates', [AdminController::class, 'emailTemplates'])->name('admin.email-templates.index');
    Route::put('/email-templates/{template}', [AdminController::class, 'updateEmailTemplate'])->name('admin.email-templates.update');
    Route::get('/email-templates/{template}/preview', [AdminController::class, 'previewEmailTemplate'])->name('admin.email-templates.preview');

    // Sales — extra tabs
    Route::get('/shipments', [AdminController::class, 'shipments'])->name('admin.shipments.index');
    Route::get('/invoices', [AdminController::class, 'invoices'])->name('admin.invoices.index');
    Route::get('/refunds', [AdminController::class, 'refunds'])->name('admin.refunds.index');
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions.index');
    Route::get('/rma', [AdminController::class, 'rma'])->name('admin.rma.index');

    // Catalog — extra tabs
    Route::get('/attributes', [AdminController::class, 'attributes'])->name('admin.attributes.index');
    Route::get('/attribute-families', [AdminController::class, 'attributeFamilies'])->name('admin.attribute-families.index');

    // Customers — extra tabs
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews.index');
    Route::delete('/reviews/{review}', [AdminController::class, 'deleteReview'])->name('admin.reviews.destroy');
    Route::get('/customer-groups', [AdminController::class, 'customerGroups'])->name('admin.customer-groups.index');
    Route::get('/gdpr', [AdminController::class, 'gdpr'])->name('admin.gdpr.index');

    // Marketing — extra tabs
    Route::get('/communications', [AdminController::class, 'communications'])->name('admin.communications.index');
    Route::get('/seo', [AdminController::class, 'seo'])->name('admin.seo.index');

    // Reporting — extra tabs
    Route::get('/reports/customers', [AdminController::class, 'customerReports'])->name('admin.reports.customers');
    Route::get('/reports/products', [AdminController::class, 'productReports'])->name('admin.reports.products');

    // Admin Preview Mode
    Route::get('/preview/enable', [AdminPreviewController::class, 'enable'])->name('admin.preview.enable');
    Route::get('/preview/disable', [AdminPreviewController::class, 'disable'])->name('admin.preview.disable');

    // CMS Pages
    Route::get('/cms/pages', [CmsPageController::class, 'index'])->name('admin.cms.pages.index');
    Route::get('/cms/pages/create', [CmsPageController::class, 'create'])->name('admin.cms.pages.create');
    Route::post('/cms/pages', [CmsPageController::class, 'store'])->name('admin.cms.pages.store');
    Route::get('/cms/pages/{page}/edit', [CmsPageController::class, 'edit'])->name('admin.cms.pages.edit');
    Route::put('/cms/pages/{page}', [CmsPageController::class, 'update'])->name('admin.cms.pages.update');
    Route::delete('/cms/pages/{page}', [CmsPageController::class, 'destroy'])->name('admin.cms.pages.destroy');
});
