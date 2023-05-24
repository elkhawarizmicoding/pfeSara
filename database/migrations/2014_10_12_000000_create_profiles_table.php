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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->string('full_name')->nullable();
            $table->enum('area', [
                'computer_science',
                'policy',
                'medicine',
                'sport',
                '',
            ])->nullable();
            $table->string('specialty')->nullable();
            $table->integer('age')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->enum('sex', ['women', 'man'])->nullable();
            $table->string('password')->nullable();

            $table->integer('nb_docs')->nullable();
            $table->integer('freq_max')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
