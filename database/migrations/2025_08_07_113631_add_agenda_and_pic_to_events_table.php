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
        Schema::table('events', function (Blueprint $table) {
            $table->text('agenda')->nullable();
            $table->string('pic')->nullable(); // Assuming PIC is a name or identifier
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['agenda', 'pic']);
        });
    }
};
