<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('drivers', 'drivers_company_id_index', function (Blueprint $table) {
            $table->index('company_id', 'drivers_company_id_index');
        });

        $this->addIndexIfMissing('tickets', 'tickets_company_id_index', function (Blueprint $table) {
            $table->index('company_id', 'tickets_company_id_index');
        });

        $this->addIndexIfMissing('tickets', 'tickets_company_id_user_email_index', function (Blueprint $table) {
            $table->index(['company_id', 'user_email'], 'tickets_company_id_user_email_index');
        });

        $this->addIndexIfMissing('users', 'users_roleable_type_roleable_id_index', function (Blueprint $table) {
            $table->index(['roleable_type', 'roleable_id'], 'users_roleable_type_roleable_id_index');
        });

        $this->addIndexIfMissing('users', 'users_email_index', function (Blueprint $table) {
            $table->index('email', 'users_email_index');
        });
    }

    public function down(): void
    {
        $this->dropIndexIfExists('drivers', 'drivers_company_id_index');
        $this->dropIndexIfExists('tickets', 'tickets_company_id_index');
        $this->dropIndexIfExists('tickets', 'tickets_company_id_user_email_index');
        $this->dropIndexIfExists('users', 'users_roleable_type_roleable_id_index');
        $this->dropIndexIfExists('users', 'users_email_index');
    }

    private function addIndexIfMissing(string $table, string $index, callable $callback): void
    {
        if ($this->indexExists($table, $index)) {
            return;
        }

        Schema::table($table, $callback);
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        if (! $this->indexExists($table, $index)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($index) {
            $blueprint->dropIndex($index);
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $database = DB::getDatabaseName();
        $row = DB::selectOne(
            'SELECT 1 AS ok FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$database, $table, $index]
        );

        return (bool) $row;
    }
};
