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
        Schema::dropIfExists('tickets');

        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->index();
            $table->string('code', 20);
            $table->string('title', 255);
            $table->string('status', 30);
            $table->string('priority', 10);
            $table->string('type', 10);
            $table->json('assigned_collaborator_ids')->nullable();
            $table->integer('est_hours')->nullable();
            $table->integer('real_hours')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

