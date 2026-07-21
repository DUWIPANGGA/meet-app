<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekaman_audio', function (Blueprint $table) {
            $table->string('akses_rekaman', 20)->default('pemilik')->after('tipe_rekaman');
        });

        Schema::create('rekaman_audio_access_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekaman_audio_id')->constrained('rekaman_audio')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['rekaman_audio_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekaman_audio_access_users');

        Schema::table('rekaman_audio', function (Blueprint $table) {
            $table->dropColumn('akses_rekaman');
        });
    }
};
