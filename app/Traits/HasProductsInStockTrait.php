<?php
  
namespace App\Traits;

use App\Models\Product;

trait HasProductsInStockTrait {

    /**
     * @param array $product
     * 
     * @return bool
     */
    function hasQtyInStock($productData){
        $qtyInStock = Product::where('id',$productData['products_id'])
                        ->pluck('qty_stock')
                        ->first();
        
        if($qtyInStock >= $productData['qty'] && $qtyInStock > 0){
            return true;
        }

        return false;
    }

    /**
     * @param int $products_id
     * @param int $qty
     * 
     * @return void
     */
    function removeQtyFromProduct($products_id, $qty): void{
        $product = Product::where('id', $products_id)->first();

        $newQty = $product['qty_stock'] - $qty;

        $product->update(['qty_stock' => $newQty]);
    }

    /**
     * @param int $products_id
     * @param int $qty
     * 
     * @return void
     */
    function addQtyToProduct($products_id, $qty): void{
        $product = Product::where('id', $products_id)->first();

        $newQty = $product['qty_stock'] + $qty;

        $product->update(['qty_stock' => $newQty]);
    }
  
}