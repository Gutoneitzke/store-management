<?php
  
namespace App\Traits;

use App\Models\UserStore;

trait MyStoresTrait {

    /**
     * @return array
     */
    public function getMyStores() {
        return UserStore::where('users_id', auth()->id())->pluck('stores_id')->toArray();
    }
  
}