<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id_client');
            $table->string('name', 15);
            $table->string('email', 255)->nullable();
            $table->string('password_hash', 255)->nullable();
            $table->enum('status', ['Standard', 'Premium'])->default('Standard');
            $table->date('date');
            $table->integer('projectsNb')->default(0);
            $table->integer('openedTickets')->default(0);
            $table->integer('totalHours')->default(0);
            $table->string('avatarColor', 7)->default('#919090');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
