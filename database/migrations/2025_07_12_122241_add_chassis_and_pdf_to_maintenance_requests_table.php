<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            // Add new column for Chassis Number after 'vehicle_model'
            $table->string('chassis_number')->nullable()->after('vehicle_model');

            // Add new column for the PDF file path after 'vehicle_images'
            $table->string('pdf_document_path')->nullable()->after('vehicle_images');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            // This allows you to undo the migration
            $table->dropColumn(['chassis_number', 'pdf_document_path']);
        });
    }
};