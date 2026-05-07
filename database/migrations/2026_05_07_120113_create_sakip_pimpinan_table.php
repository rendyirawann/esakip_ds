<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_pimpinan', function (Blueprint $table) {
            $table->bigIncrements('refpimpinan_id');
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('nama_pimpinan');
            $table->text('jabatan_pimpinan');
            $table->text('nama_wpimpinan');
            $table->text('jabatan_wpimpinan');
            $table->text('user_edit')->nullable();
            $table->dateTime('date_edit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_pimpinan');
    }
};