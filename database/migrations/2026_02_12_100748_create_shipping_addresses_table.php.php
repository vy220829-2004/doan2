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
        Schema::create('shipping_addresses', function (Blueprint $table): void{
            $table->id();
            $table->foreignId(column:'user_id')->constrained(table:'users')->onDelete(action:'cascade');
            $table->string(column:'full_name');
            $table->string(column:'phone');
            $table->string(column:'address');
            $table->string(column:'city');
            $table->string(column:'default')->default(value:'false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table:'shipping_addresses');
    }
};
