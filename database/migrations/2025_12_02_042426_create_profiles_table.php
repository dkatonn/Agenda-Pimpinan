<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            // Kolom baru sesuai permintaan
            $table->string('full_name')->comment('Nama Lengkap Pimpinan/Entitas');
            $table->string('position')->nullable()->comment('Jabatan/Posisi');
            $table->string('photo_path')->nullable()->comment('Path ke file foto profil');

            // Kolom yang dipertahankan
            $table->string('company_name')->nullable()->comment('Nama perusahaan atau entitas');
            $table->text('address')->nullable()->comment('Alamat lengkap');
            $table->string('phone')->nullable()->comment('Nomor telepon');
            $table->string('email')->nullable()->comment('Email kontak');
            $table->enum('category', ['Pimpinan', 'Staff'])->default('Staff');
            $table->string('role')->default('Staff');

   
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};