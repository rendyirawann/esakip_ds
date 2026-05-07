<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_sumberdana', function (Blueprint $table) {
            $table->bigIncrements('refsumberdana_id');
            $table->text('kode_sumberdana');
            $table->text('nama_sumberdana');
            $table->text('sumberdana_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_sumberdana');
    }
};