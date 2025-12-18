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
            // Hapus kolom yang tidak penting
            $table->dropColumn('company_name');
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->dropColumn('email');
            
            // CATATAN: full_name dan position TIDAK dihapus karena ini yang Anda butuhkan.
        });
    }

    /**
     * Reverse the migrations.
     * Menambahkan kembali kolom saat rollback.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Tambahkan kembali kolom yang dihapus saat rollback
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone', 15)->nullable(); // Menyesuaikan tipe data asli
            $table->string('email')->nullable();
            
            // Catatan: Pastikan tipe data yang dikembalikan sama dengan tipe data sebelumnya.
        });
    }
};