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
        Schema::table('analisa', function (Blueprint $table) {
            $table->float('wma')->nullable();
            $table->float('mad')->nullable();
            $table->float('mse')->nullable();
            $table->float('mape')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisa', function (Blueprint $table) {
            //
        });
    }
};
