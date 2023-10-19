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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('address_street', 150)->nullable()->after('cnpj');
            $table->string('address_neighborhood', 150)->nullable()->after('address_street');
            $table->string('address_number', 150)->nullable()->after('address_neighborhood');
            $table->string('address_complement', 150)->nullable()->after('address_number');
            $table->dropColumn('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('address_street');
            $table->dropColumn('address_neighborhood');
            $table->dropColumn('address_number');
            $table->dropColumn('address_complement');
            $table->string('address', 150);
        });
    }
};