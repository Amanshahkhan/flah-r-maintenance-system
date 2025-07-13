<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Allow 'start_date' and 'contract_date' to be null
            $table->date('start_date')->nullable()->change();
            $table->date('contract_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Revert the change if we ever roll back
            $table->date('start_date')->nullable(false)->change();
            $table->date('contract_date')->nullable(false)->change();
        });
    }
};