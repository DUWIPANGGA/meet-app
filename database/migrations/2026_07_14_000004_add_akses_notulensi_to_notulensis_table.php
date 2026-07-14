<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notulensis', function (Blueprint $table) {
            $table->string('akses_notulensi', 20)->default('participants')->after('file_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('notulensis', function (Blueprint $table) {
            $table->dropColumn('akses_notulensi');
        });
    }
};
