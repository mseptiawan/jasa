<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Hapus foreign key dulu
            $table->dropForeign(['service_id']);

            // Ubah kolom jadi string
            $table->string('service_id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // rollback ke integer
            $table->unsignedBigInteger('service_id')->change();

            // buat foreign key lagi
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }
};
