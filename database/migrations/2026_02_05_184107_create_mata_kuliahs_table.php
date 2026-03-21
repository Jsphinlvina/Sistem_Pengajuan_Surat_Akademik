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
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('kode')->unique();
            $table->string('nama')->unique();

            $table->unsignedSmallInteger('kurikulum_id');
            $table->foreign('kurikulum_id')
                  ->references('id')
                  ->on('kurikulums')
                  ->restrictOnDelete();

            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
