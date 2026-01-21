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
        Schema::create('showschedule_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->unsignedBigInteger('schedule_created')->nullable();
            $table->unsignedBigInteger('schedule_aditor_id')->nullable();
            $table->unsignedBigInteger('showschedule_id')->nullable();
            $table->string('row_ordernumber')->nullable();
            $table->string('checklist_id')->nullable();
            $table->string('parameter')->nullable();
            $table->tinyInteger('submit_type')->nullable();
            $table->string('asing_by')->nullable();
            $table->text('asingby_remarks')->nullable();
            $table->boolean('compliant')->default(0);
            $table->boolean('partial_compliant')->default(0);
            $table->boolean('non_compliant')->default(0);
            $table->string('asing_to')->nullable();
            $table->tinyInteger('schedule_status')->nullable();
            $table->tinyInteger('responded_submit_type')->nullable();
            $table->text('responded_remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showschedule_log');
    }
};
