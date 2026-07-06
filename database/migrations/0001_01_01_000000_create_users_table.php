<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('email')->unique();

            $table->text('nik')->nullable();

            $table->string('nik_hash')->nullable()->unique();

            $table->string('no_hp', 20)
                ->nullable();

            $table->text('alamat')
                ->nullable();

            $table->string('foto')
                ->nullable();

            $table->string('jabatan')
                ->nullable();

            $table->string('kode_user', 50)->nullable()->unique();
            
            $table->string('kelurahan')->nullable();
            
            $table->string('rw', 5)->nullable();
            
            $table->string('rt', 5)->nullable();

            $table->timestamp('email_verified_at')
                ->nullable();

            $table->string('password');

            $table->rememberToken();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
