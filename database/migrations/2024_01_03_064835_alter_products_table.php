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
        Schema::table("products", function (Blueprint $table) {
            $table->text("short_description")->after('description')->nullable();
            $table->text("shipping_return")->after('short_description')->nullable();
            $table->text("releated_products")->after('shipping_return')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('short_description');
            $table->dropColumn('shipping_return');
            $table->dropColumn('releated_products');
        });
    }
};
