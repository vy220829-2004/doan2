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
         Schema::create('orders', callback: function (Blueprint $table): void{
            $table->id();
            $table->foreignId(column:'user_id')->constrained(table:'users')->onDelete('cascade');
            $table->decimal('total_price', 10, 2);
            $table->string(column:'status')->default(value:'pending');
            $table->foreignId(column:'shipping_address_id')->constrained(table:'shipping_addresses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table:'orders');
    }
};
