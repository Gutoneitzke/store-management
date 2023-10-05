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
        Schema::create('products_output', function (Blueprint $table) {
            $table->id();
            $table->string('description', 150)->nullable();
            $table->integer('qty');
            $table->enum('type', ['SELL', 'OTHER']);
            $table->decimal('total_price', 10, 2);
            $table->unsignedBigInteger('customers_id');
            $table->timestamps();

            $table->foreign('customers_id')
                ->references('id')
                ->on('customers')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_output');
    }
};
