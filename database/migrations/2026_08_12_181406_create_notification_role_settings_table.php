<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_role_settings', function (Blueprint $table) {
            $table->id();
            $table->string('role')->unique();
            $table->boolean('enabled')->default(false);
            $table->timestamps();
        });

        $now = now();
        $defaults = [
            'super_admin' => true,
            'staff_admin' => true,
            'admin' => true,
            'manager' => false,
            'company_admin' => false,
            'attorney' => false,
            'driver' => true,
        ];

        foreach ($defaults as $role => $enabled) {
            DB::table('notification_role_settings')->insert([
                'role' => $role,
                'enabled' => $enabled,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_role_settings');
    }
};
