<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
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
     // BƯỚC 1.2: Thêm đoạn này vào hàm boot
        // Nếu không phải đang chạy trên máy tính cá nhân (local) thì ép xài HTTPS
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');       //
    }
}
}