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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('subcategory_id')->nullable(); // buat kolom dulu
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('job_type')->nullable();
            $table->string('experience')->nullable();
            $table->string('industry')->nullable();
            $table->string('contact')->nullable();
            $table->string('address')->nullable();
            $table->json('images')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            // bikin foreign key dengan nama unik
            $table->foreign('subcategory_id', 'fk_services_subcategory')->references('id')->on('subcategories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // hapus foreign key dulu
            $table->dropForeign(['subcategory_id']);

            // hapus kolom
            $table->dropColumn('subcategory_id');
        });
    }
};
