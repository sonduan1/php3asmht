<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrapFive();
        view()->composer('*', function ($view) {
            // Lấy dữ liệu từ database
            $categories = Category::all(); 
            $allProduct = Product::with('category')->get();
            // Truyền dữ liệu vào view header
            $view->with(compact('categories','allProduct'));
        });
    }
}
