<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // SQLite typically stores enum as a plain string, so no schema change is needed.
        if ($driver === 'sqlite') {
            return;
        }

        // MySQL / MariaDB enum alteration
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('cash','bank_transfer') NOT NULL");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            // Revert back to the previous enum values (cash/paypal)
            DB::statement("ALTER TABLE payments MODIFY payment_method ENUM('cash','paypal') NOT NULL");
        }
    }
};
