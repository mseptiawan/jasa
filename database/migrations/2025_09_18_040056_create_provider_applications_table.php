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
        Schema::create('provider_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Data identitas
            $table->string('id_card')->nullable();     // path file KTP/SIM
            $table->string('selfie')->nullable();      // foto selfie dgn KTP
            $table->string('phone_number');
            $table->string('address')->nullable();

            // Info tambahan
            $table->string('skills')->nullable();      // skill utama
            $table->string('experience')->nullable();  // pengalaman kerja
            $table->string('portfolio')->nullable();   // link atau file portfolio
            $table->string('education')->nullable();   // pendidikan terakhir
            $table->string('cv')->nullable();          // path file CV (pdf/docx)

            // Status verifikasi
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();   // catatan admin jika ditolak
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_applications');
    }
};
