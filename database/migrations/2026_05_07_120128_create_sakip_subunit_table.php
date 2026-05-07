<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_subunit', function (Blueprint $table) {
            $table->bigIncrements('refsubunit_id');
            $table->text('kode_subunit');
            $table->text('nama_subunit');
            $table->text('subunit_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_subunit');
    }
};