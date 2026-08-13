<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_settings', function (Blueprint $table) {
            $table->id();
            $table->text('recipient_emails')->nullable();
            $table->timestamps();
        });

        DB::table('support_settings')->insert([
            'recipient_emails' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('support_settings');
    }
};
