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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nrp')->unique();
            $table->string('alamat');
            $table->date('tahun_lulus')->nullable();

            $table->unsignedSmallInteger('program_studi_id');
            $table->foreign('program_studi_id')
                  ->references('id')
                  ->on('program_studis')
                  ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
