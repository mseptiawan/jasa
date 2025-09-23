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
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_highlight')->default(false)->after('price');
            $table->timestamp('highlight_until')->nullable()->after('is_highlight');
            $table->decimal('highlight_fee', 12, 2)->default(0)->after('highlight_until');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['is_highlight', 'highlight_until', 'highlight_fee']);
        });
    }
};
