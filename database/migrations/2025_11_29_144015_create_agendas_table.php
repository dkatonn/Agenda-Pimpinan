<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();

            $table->string('nama_kegiatan', 255);
            $table->date('tanggal');
            $table->string('tempat', 255);

            $table->text('keterangan')->nullable();
            $table->string('disposisi')->nullable();

            $table->enum('status', ['draft', 'published'])->default('draft');

            // Relasi opsional
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('profile_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
