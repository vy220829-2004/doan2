<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('shipping_addresses', 'fullname') && ! Schema::hasColumn('shipping_addresses', 'full_name')) {
            // Avoid doctrine/dbal requirement for renameColumn
            DB::statement("ALTER TABLE `shipping_addresses` CHANGE `fullname` `full_name` VARCHAR(255) NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('shipping_addresses', 'full_name') && ! Schema::hasColumn('shipping_addresses', 'fullname')) {
            DB::statement("ALTER TABLE `shipping_addresses` CHANGE `full_name` `fullname` VARCHAR(255) NOT NULL");
        }
    }
};
