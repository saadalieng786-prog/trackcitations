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
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_id');
            $table->string('sf_id')->nullable();
            $table->string('filename');
            $table->text('description')->nullable();
            $table->string('path');
            $table->dateTime('sf_last_modified_date')->nullable();
            $table->dateTime('last_modified_date')->nullable();
            $table->boolean('notified')->nullable();
            $table->boolean('exist')->nullable();
            $table->integer('checked')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
    }
};
