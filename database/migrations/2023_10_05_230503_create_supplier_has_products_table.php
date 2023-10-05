<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_has_products', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('products_id');
            $table->timestamps();

            $table->primary(['supplier_id', 'products_id']);

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');

            $table->foreign('products_id')
                ->references('id')
                ->on('products')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_has_products');
    }
};
