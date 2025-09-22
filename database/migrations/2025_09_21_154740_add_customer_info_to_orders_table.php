<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('customer_address')->nullable()->after('payment_method');
            $table->string('customer_phone')->nullable()->after('customer_address');
            $table->text('note')->nullable()->after('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_address', 'customer_phone', 'note']);
        });
    }
};
