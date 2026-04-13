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
            $table->increments('id_ticket');
            $table->string('code', 20);
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->integer('client_id')->index();
            $table->integer('project_id')->index();
            $table->string('status', 20)->nullable();
            $table->string('priority', 20)->nullable();
            $table->string('type', 20)->nullable();
            $table->string('time_est', 10)->nullable();
            $table->string('time_real', 10)->nullable();
            $table->date('created_at')->nullable();
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

