<?php
  
namespace App\Traits;

use App\Models\Store;

trait HasSelectedStoreTrait {

    public function getSelectedStore() {
        $mySelectedStore = session('mySelectedStore');

        return !empty($mySelectedStore) ? $mySelectedStore : false;
    }
  
}