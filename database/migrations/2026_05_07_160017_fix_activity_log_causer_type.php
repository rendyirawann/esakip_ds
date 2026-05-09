<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Hapus kolom morph UUID lama
            $table->dropColumn(['subject_type', 'subject_id', 'causer_type', 'causer_id']);
        });

        Schema::table('activity_log', function (Blueprint $table) {
            // Tambahkan kolom morph standar (BigInt)
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn(['subject_type', 'subject_id', 'causer_type', 'causer_id']);
        });

        Schema::table('activity_log', function (Blueprint $table) {
            $table->nullableUuidMorphs('subject', 'subject');
            $table->nullableUuidMorphs('causer', 'causer');
        });
    }
};
