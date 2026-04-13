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
        Schema::create('collaborators', function (Blueprint $table) {
            $table->increments('id_collab');
            $table->string('full_name', 150);
            $table->string('email', 255)->unique();
            $table->string('position', 120);
            $table->enum('work_status', ['Available', 'On project', 'Vacation / away'])->default('Available');
            $table->unsignedInteger('projects_count')->default(0);
            $table->unsignedInteger('tickets_count')->default(0);
            $table->decimal('rating', 2, 1)->nullable();
            $table->char('avatar_color', 7)->default('#919090');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborators');
    }
};
