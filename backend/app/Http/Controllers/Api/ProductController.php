<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(fn (Product $product) => $this->formatProduct($product));

        return response()->json([
            'data' => $products,
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'data' => $this->formatProduct($product),
        ]);
    }

    private function formatProduct(Product $product): array
    {
        $imagePath = trim((string) $product->image_path);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'description' => $product->description,
            'price' => (float) $product->price,
            'unit' => $product->unit,
            'stock' => (int) $product->stock,
            'category' => $product->category,
            'specifications' => $product->specifications,
            'is_active' => (bool) $product->is_active,
            'image_url' => $this->resolveImageUrl($imagePath),
        ];
    }

    private function resolveImageUrl(string $imagePath): ?string
    {
        if ($imagePath === '') {
            return null;
        }

        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }

        $normalizedPath = ltrim($imagePath, '/');

        if (str_starts_with($normalizedPath, 'storage/')) {
            return asset($normalizedPath);
        }

        if (str_starts_with($normalizedPath, 'images/')) {
            return asset($normalizedPath);
        }

        return asset('storage/'.$normalizedPath);
    }
}
