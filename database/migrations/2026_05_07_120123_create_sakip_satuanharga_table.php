<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_satuanharga', function (Blueprint $table) {
            $table->bigIncrements('refsatuanharga_id');
            $table->text('kode_satuanharga');
            $table->text('nama_satuanharga');
            $table->text('satuanharga_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_satuanharga');
    }
};