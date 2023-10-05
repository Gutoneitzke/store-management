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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('cpf', 150);
            $table->enum('type', ['ADMIN', 'STORE_OWNER', 'EMPLOYEE'])->nullable();
            $table->rememberToken();
            $table->enum('gender', ['M', 'F', 'O']);
            $table->string('address_street', 150);
            $table->string('address_number', 150);
            $table->string('address_neighborhood', 150);
            $table->string('address_complement', 150)->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
