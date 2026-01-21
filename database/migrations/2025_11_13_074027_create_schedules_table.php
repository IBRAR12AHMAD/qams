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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->date('start_date')->nullable();
            $table->string('program_id')->nullable();
            $table->integer('audit_id')->nullable();
            $table->integer('status')->default('0');
            $table->integer('created_by')->nullable();
            $table->integer('checklist_id')->nullable();
            $table->string('aditor_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
