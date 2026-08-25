<?php

use Illuminate\Http\Request;
use App\Services\Supabase\SupabaseStorageService;
// Controllers - Public
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Public\AboutController; // Controller baru dari Claude

// Controllers - Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\PredictionController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\MortalityController;
use App\Http\Controllers\Admin\SettingsController; // Controller baru dari Claude
use App\Http\Controllers\Admin\BackupController;   // Controller baru dari Claude
use App\Http\Controllers\Admin\RestoreController;  // Controller baru dari Claude
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::post('/upload-test', function (
    Request $request,
    SupabaseStorageService $storage
) {

    $request->validate([
        'file' => 'required|image'
    ]);

    return $storage->upload(
        $request->file('file'),
        'profile-photos'
    );

});
Route::get('/upload-test', function () {
    return '
        <form method="POST" enctype="multipart/form-data">
            '.csrf_field().'
            <input type="file" name="file">
            <button>Upload</button>
        </form>
    ';
});
// Controllers - Auth
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// ==========================================
// PUBLIC ROUTES
// ==========================================

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/products', [HomeController::class, 'index'])
    ->name('products.index');

Route::get('/products/{product}', [HomeController::class, 'show'])
    ->name('products.show');

// Route Tentang Kami (Fasel Aquarium)
Route::get('/tentang-kami', [AboutController::class, 'index'])
    ->name('about');

// Route untuk Syarat & Ketentuan dan Kebijakan Privasi
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// ==========================================
// MIDTRANS WEBHOOK
// ==========================================
Route::post('/test-webhook', function () {

    Log::info('TEST WEBHOOK BERHASIL');

    return response()->json([
        'success' => true
    ]);

});
Route::post('/midtrans/notification', [OrderController::class, 'notification'])
    ->name('midtrans.notification');


// ==========================================
// GUEST ROUTES (LOGIN & REGISTER)
// ==========================================

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');

    // Register
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register.store');
});


// ==========================================
// AUTH ROUTES (USER REGISTERED)
// ==========================================

Route::middleware('auth')->group(function () {

    // Dashboard Redirect
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    })->name('dashboard');

    // USER PROFILE
    Route::prefix('profile')->name('profile.')->group(function () {

        Route::get('/', [ProfileController::class, 'index'])
            ->name('index');

        Route::put('/', [ProfileController::class, 'update'])
            ->name('update');

        Route::delete('/photo', [ProfileController::class, 'deletePhoto'])
            ->name('photo.delete');
    });

    // LOGOUT
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // WISHLIST ROUTES
    Route::prefix('wishlist')->name('wishlist.')->group(function () {

        Route::get('/', [WishlistController::class, 'index'])
            ->name('index');

        Route::post('/add', [WishlistController::class, 'toggle'])
            ->name('add');

        Route::post('/toggle', [WishlistController::class, 'toggle'])
            ->name('toggle');

        Route::delete('/{id}', [WishlistController::class, 'destroy'])
            ->name('destroy');
    });

    // CART ROUTES
    Route::prefix('cart')->name('cart.')->group(function () {

        Route::get('/', [CartController::class, 'index'])
            ->name('index');

        Route::post('/add', [CartController::class, 'add'])
            ->name('add');

        Route::patch('/{cart}', [CartController::class, 'update'])
            ->name('update');

        Route::delete('/{cart}', [CartController::class, 'destroy'])
            ->name('destroy');

        Route::delete('/', [CartController::class, 'clear'])
            ->name('clear');

        Route::post('/checkout', [CartController::class, 'checkout'])
            ->name('checkout');
    });

    // ORDER ROUTES
    Route::prefix('orders')->name('orders.')->group(function () {

        Route::get('/', [OrderController::class, 'index'])
            ->name('index');

        Route::post('/checkout', [OrderController::class, 'checkout'])
            ->name('checkout');

        Route::get('/{order}', [OrderController::class, 'show'])
            ->name('show');

        Route::get('/{order}/payment', [OrderController::class, 'payment'])
            ->name('payment');

        Route::get('/{order}/success', [OrderController::class, 'success'])
            ->name('success');

        Route::get('/{order}/finish', [OrderController::class, 'finish'])
            ->name('finish');

        Route::get('/{order}/download-pdf', [OrderController::class, 'downloadPdf'])
            ->name('download-pdf');
    });
});


// ==========================================
// ADMIN ROUTES
// ==========================================

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        // ADMIN DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
            
        // ==========================================================
// PREDIKSI
// ==========================================================

Route::get('/prediction', [PredictionController::class, 'index'])
    ->name('prediction.index');

Route::get('/prediction/data', [PredictionController::class, 'getData'])
    ->name('prediction.data');

// ----------------------------------------------------------
// PREDIKSI PENJUALAN
// ----------------------------------------------------------

Route::get('/prediction/sales', [
    PredictionController::class,
    'sales'
])->name('prediction.sales');

Route::get('/prediction/sales/data', [
    PredictionController::class,
    'salesData'
])->name('prediction.sales.data');

// ----------------------------------------------------------
// UPDATE STOK
// ----------------------------------------------------------

Route::patch('/prediction/sales/products/{product}/stock', [
    PredictionController::class,
    'updateStock'
])->name('prediction.sales.stock');
        
        // ADMIN PROFILE
        Route::prefix('profile')->name('profile.')->group(function () {

            Route::get('/', [AdminProfileController::class, 'index'])
                ->name('index');

            Route::put('/', [AdminProfileController::class, 'update'])
                ->name('update');

            Route::delete('/photo', [AdminProfileController::class, 'deletePhoto'])
                ->name('photo.delete');
        });

        // PRODUCT CRUD
        Route::resource('products', AdminProductController::class);

        // MORTALITY IKAN
        Route::resource('mortality', MortalityController::class);

        // ADMIN ORDERS
        Route::prefix('orders')->name('orders.')->group(function () {

            Route::get('/', [AdminOrderController::class, 'index'])
                ->name('index');

            Route::get('/{order}', [AdminOrderController::class, 'show'])
                ->name('show');

            Route::get('/{order}/edit', [AdminOrderController::class, 'edit'])
                ->name('edit');

            Route::patch('/{order}', [AdminOrderController::class, 'update'])
                ->name('update');
        });

        // REPORTS
        Route::prefix('reports')->name('reports.')->group(function () {

            Route::get('/', [AdminReportController::class, 'index'])
                ->name('index');

            Route::get('/pdf', [AdminReportController::class, 'exportPdf'])
                ->name('pdf');

            Route::get('/excel', [AdminReportController::class, 'exportExcel'])
                ->name('excel');
        });

        // MANAGEMENT PENGATURAN (SETTINGS)
        Route::prefix('settings')->name('settings.')->group(function () {
            
            Route::get('/', [SettingsController::class, 'index'])
                ->name('index');

            Route::post('/general', [SettingsController::class, 'updateGeneral'])
                ->name('general');

            Route::post('/contact', [SettingsController::class, 'updateContact'])
                ->name('contact');

            Route::post('/identity', [SettingsController::class, 'updateIdentity'])
                ->name('identity');

            Route::post('/about', [SettingsController::class, 'updateAbout'])
                ->name('about');

            Route::post('/about/gallery/{index}', [SettingsController::class, 'uploadGallery'])
                ->name('gallery.upload');

            Route::delete('/about/gallery/{index}', [SettingsController::class, 'deleteGallery'])
                ->name('gallery.delete');
        });

        // BACKUP & RESTORE
        Route::post('/backup/download', [BackupController::class, 'download'])
            ->name('backup.download');

        Route::post('/restore/upload', [RestoreController::class, 'upload'])
            ->name('restore.upload');

        Route::post('/restore/confirm', [RestoreController::class, 'confirm'])
            ->name('restore.confirm');

        // USER MANAGEMENT
        Route::prefix('users')->name('users.')->group(function () {

            Route::get('/', [AdminUserController::class, 'index'])
                ->name('index');

            Route::get('/create', [AdminUserController::class, 'create'])
                ->name('create');

            Route::post('/', [AdminUserController::class, 'store'])
                ->name('store');

            Route::get('/{user}', [AdminUserController::class, 'show'])
                ->name('show');

            Route::get('/{user}/edit', [AdminUserController::class, 'edit'])
                ->name('edit');

            Route::put('/{user}', [AdminUserController::class, 'update'])
                ->name('update');

            Route::delete('/{user}', [AdminUserController::class, 'destroy'])
                ->name('destroy');

            Route::patch('/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
                ->name('toggle-status');

            Route::post('/{user}/reset-password', [AdminUserController::class, 'resetPassword'])
                ->name('reset-password');
        });
    });

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');
// ==========================================
// 404 FALLBACK
// ==========================================

//Route::fallback(function () {
    //return view('errors.404');
//});