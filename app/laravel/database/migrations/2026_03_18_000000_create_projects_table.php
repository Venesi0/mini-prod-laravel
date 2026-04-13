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
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 15);
            $table->integer('client_id')->index();
            $table->string('status', 15);
            $table->integer('contractHours');
            $table->integer('usedHours')->default(0);
            $table->integer('openTickets');
            $table->json('collaborator_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
