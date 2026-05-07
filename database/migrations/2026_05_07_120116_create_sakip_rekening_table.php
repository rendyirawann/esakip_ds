<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_rekening', function (Blueprint $table) {
            $table->bigIncrements('refrekening_id');
            $table->text('kode_rekening');
            $table->text('nama_rekening');
            $table->text('rekening_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_rekening');
    }
};