<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->decimal('progress_value_exceed', 8, 2)->default(0)
                  ->after('progress_value')
                  ->comment('Stores the portion of progress that exceeded the required value');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('progress_value_exceed');
        });
    }
};
