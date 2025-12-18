<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        DB::table('settings')->insert([
            ['key' => 'profile_text', 'value' => 'Bapak/Ibu Pimpinan merupakan sosok yang berdedikasi tinggi...', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'running_text', 'value' => '🎉 Selamat datang di Sistem Informasi Agenda Pimpinan...', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};