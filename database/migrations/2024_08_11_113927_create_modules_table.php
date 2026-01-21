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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_name',100)->nullable();
            $table->string('slug',100)->nullable();
            $table->string('menu_template',150)->nullable();
            $table->string('icon',50)->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('status')->default('0');
            $table->integer('order_via')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
