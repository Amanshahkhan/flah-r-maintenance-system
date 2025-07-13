<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 // In database/migrations/xxxx_add_status_to_representatives_table.php
public function up(): void
{
    Schema::table('representatives', function (Blueprint $table) {
        // By default, new representatives will be active.
        $table->timestamp('activated_at')->nullable()->after('password');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('representatives', function (Blueprint $table) {
            //
        });
    }
};
