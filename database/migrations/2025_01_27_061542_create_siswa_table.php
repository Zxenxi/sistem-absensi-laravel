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
        // Schema::create('siswa', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nisn');
        //     $table->string('nama');
        //     $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
        //     $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        //     $table->timestamps();
        // });
        
    
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('siswa');
    // }
};