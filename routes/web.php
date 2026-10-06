<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CatalogController as AdminCatalogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChildCategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShippingChargeController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SolutionController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order_number}', [CheckoutController::class, 'success'])->name('checkout.success');

/*
|--------------------------------------------------------------------------
| Customer Authentication (Web Guard)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/orders', [CustomerDashboardController::class, 'orders'])->name('customer.orders');
    Route::get('/customer/orders/{order_number}', [CustomerDashboardController::class, 'orderDetails'])->name('customer.orders.details');
    Route::post('/customer/profile/update', [CustomerDashboardController::class, 'updateProfile'])->name('customer.profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication & Management (Admin Guard)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Admin Guest routes
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    // Admin Authenticated routes
    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

        // AJAX API Helpers
        Route::get('/api/subcategories/{categoryId}', [SubCategoryController::class, 'getByCategory'])->name('admin.api.subcategories');
        Route::get('/api/childcategories/{subCategoryId}', [ChildCategoryController::class, 'getBySubCategory'])->name('admin.api.childcategories');
        Route::delete('/products/gallery-image/{id}', [AdminProductController::class, 'deleteGalleryImage'])->name('admin.products.delete-gallery-image');

        // Sliders & Hero Banners Management
        Route::resource('sliders', SliderController::class)->names('admin.sliders');
        Route::post('/sliders/{slider}/toggle', [SliderController::class, 'toggle'])->name('admin.sliders.toggle');

        // Solutions Cards Management
        Route::resource('solutions', SolutionController::class)->names('admin.solutions');
        Route::post('/solutions/{solution}/toggle', [SolutionController::class, 'toggle'])->name('admin.solutions.toggle');

        // Brand Portfolio Management
        Route::resource('brands', BrandController::class)->names('admin.brands');
        Route::post('/brands/{brand}/toggle', [BrandController::class, 'toggle'])->name('admin.brands.toggle');

        // Pages & Footer Links Management
        Route::resource('pages', AdminPageController::class)->names('admin.pages');
        Route::post('/pages/{page}/toggle', [AdminPageController::class, 'toggle'])->name('admin.pages.toggle');

        // E-Catalogs Management
        Route::resource('catalogs', AdminCatalogController::class)->names('admin.catalogs');
        Route::post('/catalogs/{catalog}/toggle', [AdminCatalogController::class, 'toggle'])->name('admin.catalogs.toggle');

        // Product Management
        Route::resource('products', AdminProductController::class)->names('admin.products');

        // Catalog Management
        Route::resource('categories', CategoryController::class)->names('admin.categories');
        Route::resource('subcategories', SubCategoryController::class)->names('admin.subcategories');
        Route::resource('childcategories', ChildCategoryController::class)->names('admin.childcategories');

        // Attributes Management (Colors & Sizes)
        Route::get('/colors', [ColorController::class, 'index'])->name('admin.colors.index');
        Route::post('/colors', [ColorController::class, 'store'])->name('admin.colors.store');
        Route::put('/colors/{color}', [ColorController::class, 'update'])->name('admin.colors.update');
        Route::delete('/colors/{color}', [ColorController::class, 'destroy'])->name('admin.colors.destroy');

        Route::get('/sizes', [SizeController::class, 'index'])->name('admin.sizes.index');
        Route::post('/sizes', [SizeController::class, 'store'])->name('admin.sizes.store');
        Route::put('/sizes/{size}', [SizeController::class, 'update'])->name('admin.sizes.update');
        Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->name('admin.sizes.destroy');

        // Order Management
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.status');
        Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('admin.orders.invoice');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

        // Order Statuses Management
        Route::resource('order-statuses', OrderStatusController::class)->names('admin.order-statuses');
        Route::post('/order-statuses/{order_status}/toggle', [OrderStatusController::class, 'toggle'])->name('admin.order-statuses.toggle');

        // Shipping Charges
        Route::get('/shipping', [ShippingChargeController::class, 'index'])->name('admin.shipping.index');
        Route::post('/shipping', [ShippingChargeController::class, 'store'])->name('admin.shipping.store');
        Route::put('/shipping/{shipping}', [ShippingChargeController::class, 'update'])->name('admin.shipping.update');
        Route::delete('/shipping/{shipping}', [ShippingChargeController::class, 'destroy'])->name('admin.shipping.destroy');

        // Coupon Management
        Route::resource('coupons', AdminCouponController::class)->names('admin.coupons');
        Route::post('/coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('admin.coupons.toggle');

        // Customer Management
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('admin.customers.index');
        Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('admin.customers.show');
        Route::post('/customers/{customer}/toggle', [AdminCustomerController::class, 'toggleStatus'])->name('admin.customers.toggle');

        // Roles, Permissions & Admins
        Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
        Route::post('/roles', [RoleController::class, 'storeRole'])->name('admin.roles.store');
        Route::put('/roles/{role}', [RoleController::class, 'updateRole'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroyRole'])->name('admin.roles.destroy');

        Route::post('/admins', [RoleController::class, 'storeAdmin'])->name('admin.admins.store');
        Route::put('/admins/{admin}', [RoleController::class, 'updateAdmin'])->name('admin.admins.update');
        Route::delete('/admins/{admin}', [RoleController::class, 'destroyAdmin'])->name('admin.admins.destroy');

        // Site Settings
        Route::get('/settings', [SiteSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [SiteSettingController::class, 'update'])->name('admin.settings.update');
    });
});
