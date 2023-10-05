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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 150)->nullable();
            $table->string('cpf_cnpj', 150)->nullable();
            $table->enum('gender', ['M', 'F', 'O']);
            $table->unsignedBigInteger('cities_id');
            $table->string('address_street', 150);
            $table->string('address_number', 150);
            $table->string('address_neighborhood', 150);
            $table->string('address_complement', 150)->nullable();
            $table->timestamps();

            $table->foreign('cities_id')
                ->references('id')
                ->on('cities')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
