<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ContactController;

Route::get("/", [UserController::class, "index"]);
Route::post("/contact", [ContactController::class, "store"])->name("contact.store");
Route::get("/product/{product}", [ProductController::class, "show"])->name("products.show");

Route::view("/register", "auth.register")->middleware("guest")->name("register");
Route::post("/register", [RegisterController::class, "store"])->middleware("guest");
Route::post("/logout", Logout::class)->middleware("auth")->name("logout");
Route::view("/login", "auth.login")->middleware("guest")->name("login");
Route::post("/login", [Login::class, "store"])->middleware("guest");

Route::middleware("auth")->group(function () {
    // Cart
    Route::get("/cart",                 [CartController::class, "index"])->name("cart.index");
    Route::post("/cart/add",            [CartController::class, "add"])->name("cart.add");
    Route::patch("/cart/items/{item}",  [CartController::class, "update"])->name("cart.update");
    Route::delete("/cart/items/{item}", [CartController::class, "remove"])->name("cart.remove");
    Route::delete("/cart/clear",        [CartController::class, "clear"])->name("cart.clear");

    // Wishlist
    Route::get("/wishlist",                [LikeController::class, "index"])->name("wishlist.index");
    Route::post("/likes/{product}/toggle", [LikeController::class, "toggle"])->name("likes.toggle");

    // Checkout & Orders
    Route::get("/checkout",       [OrderController::class, "checkout"])->name("checkout");
    Route::post("/checkout",      [OrderController::class, "place"])->name("checkout.place");
    Route::get("/orders",         [OrderController::class, "index"])->name("orders.index");
    Route::get("/orders/{order}", [OrderController::class, "show"])->name("orders.show");

    // Reviews
    Route::post("/products/{product}/reviews", [FeedbackController::class, "store"])->name("reviews.store");
    Route::delete("/reviews/{feedback}",       [FeedbackController::class, "destroy"])->name("reviews.destroy");
});

Route::middleware(["auth", "admin"])->prefix("admin")->group(function () {
    Route::get("/",                             [AdminController::class, "index"])->name("admin.dashboard");
    Route::get("/products/create",              [AdminController::class, "create"])->name("admin.products.create");
    Route::post("/products",                    [AdminController::class, "store"])->name("admin.products.store");
    Route::get("/products/{product}/edit",      [AdminController::class, "edit"])->name("admin.products.edit");
    Route::put("/products/{product}",           [AdminController::class, "update"])->name("admin.products.update");
    Route::delete("/products/{product}",        [AdminController::class, "destroy"])->name("admin.products.delete");
    Route::get("/archive",                      [AdminController::class, "archive"])->name("admin.archive");
    Route::post("/products/{id}/restore",       [AdminController::class, "restore"])->name("admin.restore");
    Route::delete("/products/{id}/force-delete",[AdminController::class, "forceDelete"])->name("admin.forceDelete");
    Route::get("/categories",                   [CategoryController::class, "index"])->name("admin.categories.index");
    Route::post("/categories",                  [CategoryController::class, "store"])->name("admin.categories.store");
    Route::put("/categories/{category}",        [CategoryController::class, "update"])->name("admin.categories.update");
    Route::delete("/categories/{category}",     [CategoryController::class, "destroy"])->name("admin.categories.destroy");
    Route::get("/orders",                       [AdminController::class, "orders"])->name("admin.orders.index");
    Route::patch("/orders/{order}/status",      [AdminController::class, "updateOrderStatus"])->name("admin.orders.status");
});