<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 // In your ..._create_maintenance_requests_table.php file
public function up(): void
{
   Schema::create('maintenance_requests', function (Blueprint $table) {
        $table->id();
        
        // Core Request Info
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('set null'); // Added contract link
        $table->string('vehicle_number');
        $table->string('vehicle_color');
        $table->string('vehicle_model');
        $table->json('vehicle_images')->nullable();
        
        // Parts Info (Old way, but we'll keep for now to avoid breaking controllers)
        $table->json('parts_select')->nullable();
        $table->text('parts_manual')->nullable();
        $table->integer('quantity')->default(1);
        
        // Workflow & Assignment Info
        $table->string('status')->default('pending');
        $table->foreignId('representative_id')->nullable()->constrained('representatives')->onDelete('set null'); // ✅ Correctly points to 'representatives'
        $table->decimal('total_cost', 10, 2)->nullable();
        
        // Timestamps
        $table->timestamp('assigned_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->timestamp('rejected_at')->nullable();
        $table->text('rejection_reason')->nullable();
        
        // Document Paths
        $table->string('parts_receipt_doc_path')->nullable();
        $table->string('install_complete_doc_path')->nullable();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};