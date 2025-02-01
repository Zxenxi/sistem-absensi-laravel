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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->onDelete('cascade'); // Ubah ini jadi nullable
            $table->foreignId('guru_id')->nullable()->constrained('guru')->onDelete('cascade');
            $table->dateTime('waktu');
            $table->string('lokasi')->nullable();
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alfa']);
            $table->string('foto_wajah')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};