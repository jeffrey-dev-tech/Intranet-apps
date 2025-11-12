<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('maintenance_modes', function (Blueprint $table) {
        $table->id();
        $table->string('route_name'); // route name like 'computer.inventory'
        $table->boolean('enabled')->default(false); // whether it's under maintenance
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_modes');
    }
};
