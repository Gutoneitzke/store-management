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
        Schema::create('products_has_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('products_id');
            $table->unsignedBigInteger('categories_id');
            $table->timestamps();

            $table->primary(['products_id', 'categories_id']);

            $table->foreign('products_id')
                ->references('id')
                ->on('products')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');

            $table->foreign('categories_id')
                ->references('id')
                ->on('categories')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_has_categories');
    }
};
