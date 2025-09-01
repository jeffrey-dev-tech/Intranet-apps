<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('it_request_tbl', function (Blueprint $table) {
    $table->id();
    $table->string('reference_no')->unique(); // New column for reference number
    $table->string('requestor_name');
    $table->string('requestor_email');
    $table->string('department');
    $table->string('issue')->nullable();
    $table->string('item_name')->nullable();
    $table->date('date_needed')->nullable();
    $table->date('plan_return_date')->nullable();
    $table->string('purchase_item_name')->nullable();
    $table->string('project_details')->nullable();
    $table->string('subsystem_title')->nullable();
    $table->string('manager_email')->nullable();
    $table->string('change_request_intranet')->nullable();
    $table->string('type_request');
    $table->text('description_of_request');
    $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
    $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Rejected'])->default('Pending');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('it_request_tbl');
    }
};
