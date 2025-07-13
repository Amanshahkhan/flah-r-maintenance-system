<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
 Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('mobile')->nullable();
    $table->string('region')->nullable();
    $table->string('address')->nullable();
    $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('set null');
    $table->string('role')->default('user');
    $table->rememberToken();
    $table->timestamps();
});


    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
