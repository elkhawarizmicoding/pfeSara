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
        Schema::create('profile_event_passes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('profile_id')->nullable();
            $table->foreign('profile_id')->references('id')->on('profiles');

            $table->unsignedBigInteger('event_pass_id')->nullable();
            $table->foreign('event_pass_id')->references('id')->on('event_passes');

            $table->double('weight')->nullable();
            $table->double('np_docs')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_event_passes');
    }
};
