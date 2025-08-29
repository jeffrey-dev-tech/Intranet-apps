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
    Schema::table('users', function (Blueprint $table) {
        $table->string('status')->nullable()->after('department'); 
        $table->string('position')->nullable()->after('status'); 
        // Use `nullable()` if it's optional
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
       $table->dropColumn(['status', 'position']);
    });
}
};
