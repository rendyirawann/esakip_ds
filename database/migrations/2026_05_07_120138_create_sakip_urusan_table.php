<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_urusan', function (Blueprint $table) {
            $table->bigIncrements('urusan_id');
            $table->text('kode_urusan');
            $table->text('nama_urusan');
            $table->text('urusan_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_urusan');
    }
};