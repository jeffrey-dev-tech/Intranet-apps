<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('team_user', function (Blueprint $table) {
            $table->decimal('progress_value', 8, 2)->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('team_user', function (Blueprint $table) {
            $table->float('progress_value')->default(0)->change();
        });
    }
};
