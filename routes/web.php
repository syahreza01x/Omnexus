<?php

use App\Http\Controllers\CartController;
use App\Models\Product;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::query()
        ->where('is_active', true)
        ->latest()
        ->take(8)
        ->get();

    $cart = session('cart', []);
    $cartProducts = collect();
    $cartCount = 0;
    $cartSubtotal = 0;

    if (! empty($cart)) {
        $cartProducts = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $cartCount = collect($cart)->sum('quantity');
        $cartSubtotal = collect($cart)->reduce(function ($carry, $item, $productId) use ($cartProducts) {
            $product = $cartProducts->get((int) $productId);

            if (! $product) {
                return $carry;
            }

            return $carry + ((int) $item['quantity'] * (int) $product->price);
        }, 0);
    }

    return view('beranda', compact('products', 'cart', 'cartProducts', 'cartCount', 'cartSubtotal'));
})->name('beranda');

Route::get('/dashboard', function () {
    return redirect()->route('beranda');
})->middleware(['auth'])->name('dashboard');

Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

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
