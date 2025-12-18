<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menghapus kolom yang tidak diperlukan.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->dropColumn('email');
        });
    }

    /**
     * Reverse the migrations.
     * Menambahkan kembali kolom saat rollback.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone', 15)->nullable(); 
            $table->string('email')->nullable();
            
        });
    }
};