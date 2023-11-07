<?php
  
namespace App\Traits;

trait HasSelectedStoreTrait {

    /**
     * @return array or bool
     */
    public function getSelectedStore() {
        $mySelectedStore = session('mySelectedStore');

        return !empty($mySelectedStore) ? $mySelectedStore : false;
    }
  
}