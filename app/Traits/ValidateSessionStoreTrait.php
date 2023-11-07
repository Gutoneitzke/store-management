<?php
  
namespace App\Traits;

use App\Models\UserStore;
use Illuminate\Support\Facades\Route;

trait ValidateSessionStoreTrait {

    /**
     * @return bollean
     */
    public function validateSessionStore($data) {
        $routesToShowOnlyMyOwnerStores = ['/employers'];

        $currentRoute = Route::getCurrentRoute();
        $prefix = $currentRoute->getPrefix();

        if(in_array($prefix, $routesToShowOnlyMyOwnerStores)){
            $store = UserStore::where('users_id', auth()->id())
                ->where('stores_id', $data->id)
                ->where('type', 'STORE_OWNER')
                ->first();
            if(empty($store)){
                return false;
            }
        }
        return true;
    }
  
}