<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('app_settings')) {
            Schema::create('app_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string');
                $table->timestamps();
            });
        }

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'approval_status')) {
                $table->string('approval_status')->default('approved');
            }

            if (!Schema::hasColumn('products', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }

            if (!Schema::hasColumn('products', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
            }

            if (!Schema::hasColumn('products', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }

            if (!Schema::hasColumn('products', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable();
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'tracking_code')) {
                $table->string('tracking_code')->nullable();
            }

            if (!Schema::hasColumn('orders', 'tracking_carrier')) {
                $table->string('tracking_carrier')->nullable();
            }

            if (!Schema::hasColumn('orders', 'tracking_status')) {
                $table->string('tracking_status')->nullable();
            }

            if (!Schema::hasColumn('orders', 'tracking_url')) {
                $table->string('tracking_url')->nullable();
            }

            if (!Schema::hasColumn('orders', 'admin_notes')) {
                $table->text('admin_notes')->nullable();
            }

            if (!Schema::hasColumn('orders', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable();
            }
        });

        if (!Schema::hasTable('order_tracking_events')) {
            Schema::create('order_tracking_events', function (Blueprint $table) {
                $table->id();

                $table->foreignId('order_id')
                    ->constrained('orders')
                    ->cascadeOnDelete();

                $table->unsignedBigInteger('admin_user_id')->nullable();

                $table->string('status');
                $table->string('location')->nullable();
                $table->string('tracking_code')->nullable();
                $table->string('tracking_carrier')->nullable();
                $table->string('tracking_url')->nullable();
                $table->text('description')->nullable();
                $table->timestamp('event_at')->nullable();

                $table->timestamps();
            });
        }

        $settings = [
            [
                'key' => 'require_product_approval',
                'value' => '0',
                'type' => 'boolean',
            ],
            [
                'key' => 'marketplace_enabled',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'key' => 'checkout_enabled',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'key' => 'affiliation_enabled',
                'value' => '1',
                'type' => 'boolean',
            ],
        ];

        foreach ($settings as $setting) {
            if (!DB::table('app_settings')->where('key', $setting['key'])->exists()) {
                DB::table('app_settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        DB::table('products')
            ->whereNull('approval_status')
            ->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('order_tracking_events');
        Schema::dropIfExists('app_settings');
    }
};