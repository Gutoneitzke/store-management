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
        Schema::create('products_has_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_id');
            $table->unsignedBigInteger('sales_id');
            $table->decimal('unity_price', 10, 2);
            $table->timestamps();

            $table->foreign('products_id')
                ->references('id')
                ->on('products')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');

            $table->foreign('sales_id')
                ->references('id')
                ->on('products_output')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_has_sales');
    }
};
