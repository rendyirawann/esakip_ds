<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_unit_kerja', function (Blueprint $table) {
            $table->text('id')->primary();
            $table->text('keterangan');
            $table->text('nama')->nullable();
            $table->text('kode')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_unit_kerja');
    }
};