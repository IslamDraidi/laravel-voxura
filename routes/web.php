
use Illuminate\\Support\\Facades\\Route;
use App\\Http\\Controllers\\Auth\\RegisterController;
use App\\Http\\Controllers\\UserController;
use App\\Http\\Controllers\\Auth\\Logout;
use App\\Http\\Controllers\\Auth\\Login;
use App\\Http\\Controllers\\AdminController;
use App\\Http\\Controllers\\ProductController;

Route::get("/", [UserController::class, "index"]);

// Product Detail
Route::get("/product/{product}", [ProductController::class, "show"])->name("products.show");

Route::view("/register", "auth.register")
    ->middleware("guest")
    ->name("register");

Route::post("/register", [RegisterController::class, "store"])
    ->middleware("guest");

// Logout route
Route::post("/logout", Logout::class)
    ->middleware("auth")
    ->name("logout");

// Login route
Route::view("/login", "auth.login")
    ->middleware("guest")
    ->name("login");

Route::post("/login", [Login::class, "store"])
    ->middleware("guest");

// Admin Routes
Route::middleware(["auth", "admin"])->prefix("admin")->group(function () {
    Route::get("/", [AdminController::class, "index"])->name("admin.dashboard");
    Route::get("/products/create", [AdminController::class, "create"])->name("admin.products.create");
    Route::post("/products", [AdminController::class, "store"])->name("admin.products.store");
    Route::get("/products/{product}/edit", [AdminController::class, "edit"])->name("admin.products.edit");
    Route::put("/products/{product}", [AdminController::class, "update"])->name("admin.products.update");
    Route::delete("/products/{product}", [AdminController::class, "destroy"])->name("admin.products.delete");
    Route::get("/archive", [AdminController::class, "archive"])->name("admin.archive");
    Route::post("/products/{id}/restore", [AdminController::class, "restore"])->name("admin.restore");
});'''
