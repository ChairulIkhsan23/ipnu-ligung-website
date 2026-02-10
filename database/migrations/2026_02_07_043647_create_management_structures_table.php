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
        Schema::create('management_structures', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_anggota', 50)->unique()->nullable(); 
            $table->string('name');
            $table->string('position');
            $table->string('photo')->nullable();
            $table->string('alamat');
            $table->string('no_telp')->nullable();
            $table->text('motto')->nullable();
            $table->year('start_year');
            $table->year('end_year')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['status']);
            $table->index(['nomor_anggota']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_structures');
    }
};
