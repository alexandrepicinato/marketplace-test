<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);

            $table->boolean('active')->default(true);

            $table->boolean('accepts_affiliation')->default(false);
            $table->decimal('commission_percentage', 5, 2)->nullable();

            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('path');
            $table->boolean('is_main')->default(false);

            $table->timestamps();
        });

        Schema::create('product_affiliates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('affiliate_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('affiliate_code')->unique();
            $table->unsignedBigInteger('clicks')->default(0);

            $table->enum('status', ['active', 'canceled'])->default('active');

            $table->timestamps();

            $table->unique(['product_id', 'affiliate_user_id']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('buyer_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('seller_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('affiliate_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('affiliate_code')->nullable();

            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('commission_percentage', 5, 2)->nullable();
            $table->decimal('commission_amount', 10, 2)->default(0);

            $table->string('status')->default('pending');

            $table->timestamps();
        });

        Schema::create('order_payment_infos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->string('payment_method');
            $table->string('payer_name');
            $table->string('payer_cpf')->nullable();

            $table->string('card_last_four')->nullable();
            $table->string('card_brand')->nullable();
            $table->integer('installments')->default(1);

            $table->text('notes')->nullable();

            $table->timestamps();
        });

        Schema::create('order_shipping_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->string('zipcode')->nullable();
            $table->string('street');
            $table->string('number');
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('phone');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_shipping_addresses');
        Schema::dropIfExists('order_payment_infos');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_affiliates');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
    }
};