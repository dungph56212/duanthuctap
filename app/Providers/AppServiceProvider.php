<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Helper function để xử lý URL ảnh sản phẩm
        if (!function_exists('productImageUrl')) {
            function productImageUrl($imageUrl, $default = null) {
                if (empty($imageUrl)) {
                    return $default ?: 'https://via.placeholder.com/600x800/4CAF50/FFFFFF?text=No+Image';
                }
                
                if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    return $imageUrl;
                }
                
                return Storage::url($imageUrl);
            }
        }
    }
}
