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
       Schema::create('pengajuans', function (Blueprint $table) {
        $table->smallIncrements('id');

        $table->unsignedSmallInteger('periode_semester_id');
        $table->foreign('periode_semester_id')
              ->references('id')
              ->on('periode_semesters')
              ->restrictOnDelete();

        $table->unsignedSmallInteger('mahasiswa_id');
        $table->foreign('mahasiswa_id')
              ->references('id')
              ->on('mahasiswas')
              ->restrictOnDelete();

        $table->unsignedSmallInteger('template_surat_id');
        $table->foreign('template_surat_id')
              ->references('id')
              ->on('template_surats')
              ->restrictOnDelete();

        $table->unsignedSmallInteger('mata_kuliah_id')->nullable();
        $table->foreign('mata_kuliah_id')
              ->references('id')
              ->on('mata_kuliahs')
              ->nullOnDelete();

        $table->smallInteger('status')->default(0);
        $table->string('catatan')->nullable();
        $table->string('file_path')->nullable();

        $table->unsignedSmallInteger('user_id')->nullable();
        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->nullOnDelete();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
