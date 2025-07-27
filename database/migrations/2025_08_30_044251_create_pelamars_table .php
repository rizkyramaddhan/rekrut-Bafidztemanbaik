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
        Schema::create('pelamars', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email');
            $table->string('telepon');
            $table->unsignedBigInteger('posisi');
$table->foreign('posisi')->references('id')->on('posisis')->onDelete('cascade');
            $table->string('status')->default('proses'); // <- status bisa: proses, interview, training, ditolak
            $table->string('cv');
            $table->string('ktp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelamars');
    }
};
