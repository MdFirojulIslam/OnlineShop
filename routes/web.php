<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome'); 
// });

Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/getproduct/{slug}', [ShopController::class, 'product'])->name('front.product');

Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/add_to_cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update_cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/delete_item', [CartController::class, 'deleteItem'])->name('front.deleteItem.cart');
Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::post('/process_checkout', [CartController::class, 'processCheckout'])->name('front.processCheckout');
Route::get('/thanks/{orderId}', [CartController::class, 'thankyou'])->name('front.thankyou');
Route::post('/get_order_summary', [CartController::class, 'getOrderSummary'])->name('front.getOrderSummary');

Route::group(['prefix' => 'account'], function () {

    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', [AuthController::class, 'login'])->name('account.login');
        Route::get('/login', [AuthController::class, 'authenticate'])->name('account.authenticate');

        Route::get('/register', [AuthController::class, 'register'])->name('account.register');
        Route::get('/process_register', [AuthController::class, 'processRegister'])->name('account.processRegister');
   
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
        
        Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');
    });
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function () {

        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        //categories route
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

        //subcategory routes
        Route::get('/sub_categories', [SubCategoryController::class, 'index'])->name('sub_categories.index');
        Route::get('/sub_categories/create', [SubCategoryController::class, 'create'])->name('sub_categories.create');
        Route::post('/sub_categories', [SubCategoryController::class, 'store'])->name('sub_categories.store');
        Route::get('/sub_categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub_categories.edit');
        Route::put('/sub_categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub_categories.update');
        Route::delete('/sub_categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub_categories.delete');

        //Brands
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.delete');

        //Product 
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
        Route::put('/products/{products}', [ProductController::class, 'update'])->name('products.update');
        Route::get('/get_products', [ProductController::class, 'getProducts'])->name('products.getProducts');

        Route::get('/product_subcategories', [ProductSubCategoryController::class, 'index'])->name('product_subcategories.index');
        
        //shipping routes
        Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/shipping', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/shipping/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
        Route::put('/shipping/{id}', [ShippingController::class, 'update'])->name('shipping.update');
        Route::delete('/shipping/{id}', [ShippingController::class, 'destroy'])->name('shipping.delete');

        //coupon code routes
        Route::get('/coupons', [DiscountCodeController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create', [DiscountCodeController::class, 'create'])->name('coupons.create');
        Route::post('/coupons', [DiscountCodeController::class, 'store'])->name('coupons.store');
        // Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        // Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
        // Route::put('/products/{products}', [ProductController::class, 'update'])->name('products.update');
        // Route::get('/get_products', [ProductController::class, 'getProducts'])->name('products.getProducts');

        //Orders route
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

        //Users route
        Route::get('/discounts', [DiscountController::class, 'index'])->name('discount.index');

        //Users route
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        //Pages route
        Route::get('/pages', [PagesController::class, 'index'])->name('pages.index');

        //temp-images.create
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
    });
});
