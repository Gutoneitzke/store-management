<?php

namespace App\Providers;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class InertiaDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Inertia::share('myStores', function () {
            if (Auth::check()) {
                $userId = Auth::id();
                $myStores = Store::with('city')
                    ->join('users_has_stores', 'stores.id', '=', 'users_has_stores.stores_id')
                    ->where('users_has_stores.users_id', $userId)
                    ->select('stores.*')
                    ->get();

                return $myStores;
            } else {
                return null;
            }
        });

        Inertia::share('mySelectedStore', function () {
            if (!empty(session('mySelectedStore'))) {
                return session('mySelectedStore');
            } else {
                return null;
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
