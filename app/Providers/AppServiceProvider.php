<?php

namespace App\Providers;

use App\CategoryGroup;
use App\CategoryProduct;
use App\Bill;
use App\Promotion;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \URL::forceScheme('https');
        schema::defaultStringLength(191);

        $cateGroup = CategoryGroup::where('enable',1)->get();
        view()->share('cateGroup', $cateGroup);
        $cateProduct = CategoryProduct::where('enable',1)->get();
        view()->share('cateProduct', $cateProduct);

        $countBill_CTT = Bill::where('billStatus',1)->count();
        view()->share('countBill_CTT', $countBill_CTT);

        $promotion = Promotion::where('enable',1)->get();
        view()->share('promotion', $promotion);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
