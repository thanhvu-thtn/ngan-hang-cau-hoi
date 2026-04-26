<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('settings', 'system_settings');
    }

    public function down(): void
    {
        Schema::rename('system_settings', 'settings');
    }
};
