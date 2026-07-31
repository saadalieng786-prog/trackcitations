<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('birthdate', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip', 100)->nullable();
            $table->string('dl_number', 100)->nullable();
            $table->string('class_commercial', 100)->nullable();
            $table->string('vehicle_lic_no', 100)->nullable();
            $table->string('citation_type', 100)->nullable();
            $table->unsignedBigInteger('violation_id');
            $table->string('location_violation', 100)->nullable();
            $table->string('city_county_occurrence', 100)->nullable();
            $table->string('speed_approx', 100)->nullable();
            $table->string('arresting_officer_name', 100)->nullable();
            $table->string('note', 1000)->nullable();
            $table->string('file', 100)->nullable();
            $table->string('path', 100)->nullable();
            $table->dateTime('date_time')->nullable();
            $table->string('user_email', 100)->nullable();
            $table->string('indicator', 100)->nullable();
            $table->string('disposition__c', 99)->nullable();
            $table->string('confirmed__c', 99)->nullable();
            $table->string('canceled__c', 99)->nullable();
            $table->string('lawyer_email', 100)->nullable();
            $table->string('admin_note', 200)->nullable();
            $table->string('citation_no', 100)->nullable();
            $table->integer('status')->default(1);
            $table->string('updated_by', 50)->default('admin');
            $table->dateTime('court_date')->nullable();
            $table->string('court_address', 500)->nullable();
            $table->string('court_phone', 20)->nullable();
            $table->string('ticket_dispo', 255)->nullable();
            $table->date('date_issued')->nullable();
            $table->string('court_name', 255)->nullable();
            $table->string('county', 255)->nullable();
            $table->string('ticket_number', 255)->nullable();
            $table->unsignedBigInteger('attorney_id')->nullable();
            $table->string('road_side_inspection', 255)->nullable();
            $table->string('road_side_inspection_results', 255)->nullable();
            $table->string('sales_agent', 255)->nullable();
            $table->string('fname', 255)->nullable();
            $table->string('lname', 255)->nullable();
            $table->string('sales_agent_name', 255)->nullable();
            $table->string('sales_agent_email', 255)->nullable();
            $table->unsignedBigInteger('sales_agent_id')->nullable();
            $table->string('sf_id', 100)->nullable();
            $table->string('dataq_number__c', 255)->nullable();
            $table->string('roadside_inspection_number__c', 255)->nullable();
            $table->string('ticket_type', 255)->nullable();
            $table->float('beginning_fine_amount')->nullable();
            $table->float('final_fine_amount', 10, 2)->nullable();
            $table->string('processor_name', 255)->nullable();
            $table->string('processor_email', 255)->nullable();
            $table->string('processor_ph_number', 200)->nullable();
            $table->text('processor_notes_to_attorney')->nullable();
            $table->string('total_dver_points__c', 299)->nullable();
            $table->string('total_dver_points_removed__c', 299)->nullable();
            $table->string('attorney_response', 299)->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
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
