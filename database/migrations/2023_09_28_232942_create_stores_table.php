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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('cnpj', 150);
            $table->string('description', 45)->nullable();
            $table->string('address', 150);
            $table->unsignedBigInteger('cities_id');
            $table->timestamps();

            $table->foreign('cities_id')
                ->references('id')
                ->on('cities')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
