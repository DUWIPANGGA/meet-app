<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_audios', function (Blueprint $table) {
            $table->text('transcript')->nullable()->after('file_size_bytes');
        });
    }

    public function down(): void
    {
        Schema::table('live_audios', function (Blueprint $table) {
            $table->dropColumn('transcript');
        });
    }
};
