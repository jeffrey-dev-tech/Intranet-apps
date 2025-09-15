<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItRequestApprovalTable extends Migration
{
    public function up()
    {
        Schema::create('it_request_approval', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no'); // Connect by reference_no
            $table->string('approved_by')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Completed'])->default('Pending'); // ✅ Added Completed
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('reference_no'); // Optional index for performance
        });
    }

    public function down()
    {
        Schema::dropIfExists('it_request_approval');
    }
}
