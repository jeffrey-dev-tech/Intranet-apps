<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_request_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->integer('qty');
            $table->date('date_needed');
            $table->date('date_plan_return');
            $table->string('email');
            $table->string('department');
            $table->string('requestor_name');
            $table->string('item_description');
            $table->string('purpose');
            $table->date('date_done')->nullable(); // optional
            $table->string('status')->default('pending'); // example default
            $table->timestamps(); // adds created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_request_tbl');
    }
};
