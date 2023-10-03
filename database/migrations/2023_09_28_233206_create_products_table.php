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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('description', 45)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->integer('qty_stock');
            $table->unsignedBigInteger('stores_id');
            $table->string('code', 255);
            $table->string('photo', 255)->nullable();
            $table->timestamps();

            $table->foreign('stores_id')
                ->references('id')
                ->on('stores')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
