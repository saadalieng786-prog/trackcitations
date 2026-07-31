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
        Schema::create('sales_forces', function (Blueprint $table) {
            $table->id();
            $table->string('sf_last_sync_time')->nullable();
            $table->string('sf_att_last_sync_time')->nullable();
            $table->string('sf_file_last_sync_time')->nullable();
            $table->text('client_id')->nullable();
            $table->text('client_secret')->nullable();
            $table->text('redirect_uri')->nullable();
            $table->text('login_uri')->nullable();
            $table->text('sf_access_id')->nullable();
            $table->text('sf_access_token');
            $table->text('sf_refresh_token');
            $table->text('sf_instance_url');
            $table->text('sf_signature')->nullable();
            $table->string('sf_issued_at')->nullable();
            $table->string('sf_account_activity_synced_at')->nullable();
            $table->string('sf_contact_activity_synced_at')->nullable();
            $table->boolean('status')->index();
            $table->text('reason')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_forces');
    }
};
