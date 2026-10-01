<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function sitemap()
    {
        $products = Cache::remember('seo.sitemap.products', now()->addMinutes(30), fn () => Product::select('id', 'updated_at')->latest('updated_at')->get());
        return response()->view('seo.sitemap', compact('products'))->header('Content-Type', 'application/xml');
    }
}
