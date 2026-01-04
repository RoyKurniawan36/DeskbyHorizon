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
        Schema::table('orders', function (Blueprint $table) {
            // We change the column to a simple string to give you maximum flexibility,
            // or you can redefine the ENUM with all your new stages.
            $table->string('status')->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert back if necessary
            $table->enum('status', ['pending', 'approved', 'delivered'])->change();
        });
    }
};
