<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // Ubah foreign key dari 'siswa' menjadi 'users'
            $table->foreignId('siswa_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('waktu');
            $table->string('lokasi')->nullable();
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat']);
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