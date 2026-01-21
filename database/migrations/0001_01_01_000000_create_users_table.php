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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable()->default(0);
            $table->integer('employee_old_id')->nullable()->default(0);
            $table->string('name')->nullable();
            $table->integer('sap_id')->nullable()->default(0);
            $table->string('specilaity')->nullable();
            $table->string('sub_specilaity')->nullable();
            $table->string('semester')->nullable();
            $table->string('session_id')->nullable();
            $table->string('strategic_unit')->nullable();
            $table->string('designation')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->string('su_head')->nullable();
            $table->integer('functionally_reports_to')->nullable()->default(0);
            $table->string('functional_head_name')->nullable();
            $table->integer('administratively_reports_to')->nullable()->default(0);
            $table->string('admin_head_name')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->integer('status')->nullable()->default(1);
            $table->integer('role_id')->nullable();
            $table->string('address')->nullable();
            $table->string('unique_id')->nullable();
            $table->string('org_o')->nullable();
            $table->string('profile_img')->nullable();
            $table->string('authenticated_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
