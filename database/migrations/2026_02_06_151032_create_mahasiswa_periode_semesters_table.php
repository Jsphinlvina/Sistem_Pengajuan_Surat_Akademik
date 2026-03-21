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
         Schema::create('mahasiswa_periode_semesters', function (Blueprint $table) {
            $table->smallIncrements('id');

            $table->unsignedSmallInteger('mahasiswa_id');
            $table->foreign('mahasiswa_id')
                  ->references('id')
                  ->on('mahasiswas')
                  ->restrictOnDelete();

            $table->unsignedSmallInteger('periode_semester_id');
            $table->foreign('periode_semester_id')
                  ->references('id')
                  ->on('periode_semesters')
                  ->restrictOnDelete();

            $table->smallInteger('status')->default(1);
            $table->string('deskripsi')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'periode_semester_id'], 'mhs_periode_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_periode_semester');
    }
};
