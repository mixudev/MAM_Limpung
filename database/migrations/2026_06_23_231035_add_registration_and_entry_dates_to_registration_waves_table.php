<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_waves', function (Blueprint $table) {
            $table->date('registration_date')->nullable()->after('end_date');
            $table->date('entry_date')->nullable()->after('registration_date');
        });
    }

    public function down(): void
    {
        Schema::table('registration_waves', function (Blueprint $table) {
            $table->dropColumn(['registration_date', 'entry_date']);
        });
    }
};
