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
        Schema::create('umkm_ratings', function (Blueprint $table) {
            $table->id();
            $table->uuid('umkm_id');
            $table->string('nama_reviewer');
            $table->integer('rating')->unsigned()->comment('1 to 5 stars');
            $table->text('ulasan')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();

            $table->foreign('umkm_id')->references('id_umkm')->on('umkms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm_ratings');
    }
};
