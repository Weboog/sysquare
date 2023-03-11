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
        Schema::table('item_supplier', function (Blueprint $table) {
            Schema::create('item_supplier', function (Blueprint $table) {
                $table->unsignedBigInteger('item_id');
                $table->unsignedBigInteger('supplier_id');
                $table->double('price');

                $table->unique(['item_id', 'supplier_id']);

                $table->foreign('item_id')->references('id')->on('items');
                $table->foreign('supplier_id')->references('id')->on('suppliers');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('item_supplier');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
