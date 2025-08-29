<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTblTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('computer_name')->nullable();
            $table->string('cost', 12, 2)->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('year')->nullable();
            $table->string('user_name')->nullable();
            $table->string('position')->nullable();
            $table->string('dept_region')->nullable();
            $table->string('brand_model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('tagged')->nullable();
            $table->string('tag_date')->nullable();
            $table->string('tagged_name')->nullable();
            $table->string('os')->nullable();
            $table->string('domain_name')->nullable();
            $table->string('antivirus')->nullable();
            $table->string('ms_office')->nullable();
            $table->string('processor')->nullable();
            $table->string('hdd')->nullable();
            $table->string('ssd')->nullable();
            $table->string('memory')->nullable();
            $table->string('warranty')->nullable();
            $table->string('monitor')->nullable();
            $table->string('mouse')->nullable();
            $table->string('bios_admin_password')->nullable();
            $table->string('bios_user_password')->nullable();
            $table->string('donated_disposed')->nullable();
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_tbl');
    }
}
