<?php

namespace App\Providers;

use App\Models\Store;
use App\Traits\ValidateSessionStoreTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class InertiaDataServiceProvider extends ServiceProvider
{
    use ValidateSessionStoreTrait;

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
                    ->select('stores.*','users_has_stores.type')
                    ->get();
                $myStores->push(['id' => 0, 'name' => 'Todos']);

                return $myStores;
            } else {
                return null;
            }
        });

        Inertia::share('mySelectedStore', function () {
            $s = session('mySelectedStore');

            if (!empty($s) && $this->validateSessionStore($s)) {
                return $s;
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
