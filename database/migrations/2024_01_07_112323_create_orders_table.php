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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->double('subtotal',10,2);
            $table->double('shipping',10,2)->nullable();
            $table->string('coupon_code')->nullable();
            $table->double('discount',10,2)->nullable();
            $table->double('grand_total',10,2)->nullable();

            // user data
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('Mobile');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->string('address');
            $table->string('Appartment')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');

            $table->text('notes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
