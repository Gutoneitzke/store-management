<?php
  
namespace App\Traits;

use App\Models\Product;
use App\Models\UserStore;

trait GenerateRandomNumberTrait {

    /**
     * @return string
     */
    public function generateRandomNumber() {
        do {
            $randomNumber = mt_rand(100000, 999999);

            $existeNumero = Product::where('code', $randomNumber)->exists();
        } while ($existeNumero);

        return $randomNumber;
    }
  
}