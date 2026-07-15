<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChatController;
use App\Models\Product;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::query()
        ->where('is_active', true)
        ->latest()
        ->take(8)
        ->get();

    $allProducts = Product::query()->where('is_active', true)->get();

    $cart = session('cart', []);
    $cartProducts = collect();
    $cartCount = 0;
    $cartSubtotal = 0;

    if (! empty($cart)) {
        $productIds = collect($cart)->pluck('product_id')->unique()->toArray();
        $cartProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $cartCount = collect($cart)->sum('quantity');
        $cartSubtotal = collect($cart)->reduce(function ($carry, $item) use ($cartProducts) {
            $product = $cartProducts->get((int) $item['product_id']);
            if (! $product) return $carry;
            
            $price = (int) $product->price;
            $size = $item['size'] ?? null;
            if ($size && $product->category !== 'Aksesoris') {
                if ($size === 'M') $price += 5000;
                elseif ($size === 'L') $price += 10000;
                elseif ($size === 'XL') $price += 20000;
                elseif ($size === 'XXL') $price += 30000;
            }
            
            return $carry + ((int) $item['quantity'] * $price);
        }, 0);
    }

    return view('beranda', compact('products', 'allProducts', 'cart', 'cartProducts', 'cartCount', 'cartSubtotal'));
})->name('beranda');

Route::get('/products', function (\Illuminate\Http\Request $request) {
    $query = Product::query()->where('is_active', true);
    
    $category = $request->query('category');
    if ($category) {
        $query->where('category', $category);
    }
    
    $products = $query->latest()->get();
    $categories = Product::query()->where('is_active', true)->select('category')->distinct()->pluck('category')->sort()->values();
    
    $cart = session('cart', []);
    $cartProducts = collect();
    $cartCount = 0;
    $cartSubtotal = 0;

    if (! empty($cart)) {
        $productIds = collect($cart)->pluck('product_id')->unique()->toArray();
        $cartProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $cartCount = collect($cart)->sum('quantity');
        $cartSubtotal = collect($cart)->reduce(function ($carry, $item) use ($cartProducts) {
            $product = $cartProducts->get((int) $item['product_id']);
            if (! $product) return $carry;
            
            $price = (int) $product->price;
            $size = $item['size'] ?? null;
            if ($size && $product->category !== 'Aksesoris') {
                if ($size === 'M') $price += 5000;
                elseif ($size === 'L') $price += 10000;
                elseif ($size === 'XL') $price += 20000;
                elseif ($size === 'XXL') $price += 30000;
            }
            
            return $carry + ((int) $item['quantity'] * $price);
        }, 0);
    }

    return view('products.index', compact('products', 'categories', 'category', 'cart', 'cartProducts', 'cartCount', 'cartSubtotal'));
})->name('products.index');

Route::get('/dashboard', function () {
    return redirect()->route('beranda');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartKey}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartKey}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    // Custom Orders
    Route::prefix('custom-orders')->name('custom-orders.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CustomOrderController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\CustomOrderController::class, 'store'])->name('store');
        Route::get('/{customOrder}', [\App\Http\Controllers\CustomOrderController::class, 'show'])->name('show');
        Route::post('/{customOrder}/revisions', [\App\Http\Controllers\CustomOrderController::class, 'storeRevision'])->name('revisions.store');
        Route::post('/{customOrder}/approve', [\App\Http\Controllers\CustomOrderController::class, 'approve'])->name('approve');
        Route::get('/{customOrder}/checkout', [\App\Http\Controllers\CustomOrderController::class, 'checkout'])->name('checkout');
        Route::post('/{customOrder}/checkout', [\App\Http\Controllers\CustomOrderController::class, 'processCheckout'])->name('process-checkout');
    });

    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders (Riwayat Pesanan)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{transaction}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{transaction}/proof', [OrderController::class, 'uploadProof'])->name('orders.upload-proof');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/basic', [ProfileController::class, 'updateBasic'])->name('profile.basic.update');
    Route::patch('/profile/security/email', [ProfileController::class, 'updateEmail'])->name('profile.email.update');
    
    // Address management
    Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('address.store');
    Route::patch('/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('address.update');
    Route::post('/addresses/{address}/set-default', [ProfileController::class, 'setDefaultAddress'])->name('address.set-default');
    Route::delete('/addresses/{address}', [ProfileController::class, 'deleteAddress'])->name('address.delete');
    
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

