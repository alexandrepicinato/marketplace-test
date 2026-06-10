<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'account_status')) {
                $table->string('account_status')->default('active');
            }

            if (!Schema::hasColumn('users', 'suspended_until')) {
                $table->timestamp('suspended_until')->nullable();
            }

            if (!Schema::hasColumn('users', 'suspension_reason')) {
                $table->text('suspension_reason')->nullable();
            }

            if (!Schema::hasColumn('users', 'disabled_at')) {
                $table->timestamp('disabled_at')->nullable();
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'commission_status')) {
                $table->string('commission_status')->default('pending');
            }

            if (!Schema::hasColumn('orders', 'commission_validated_at')) {
                $table->timestamp('commission_validated_at')->nullable();
            }

            if (!Schema::hasColumn('orders', 'commission_validated_by')) {
                $table->unsignedBigInteger('commission_validated_by')->nullable();
            }

            if (!Schema::hasColumn('orders', 'commission_notes')) {
                $table->text('commission_notes')->nullable();
            }

            if (!Schema::hasColumn('orders', 'commission_paid_at')) {
                $table->timestamp('commission_paid_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'commission_paid_at')) {
                $table->dropColumn('commission_paid_at');
            }

            if (Schema::hasColumn('orders', 'commission_notes')) {
                $table->dropColumn('commission_notes');
            }

            if (Schema::hasColumn('orders', 'commission_validated_by')) {
                $table->dropColumn('commission_validated_by');
            }

            if (Schema::hasColumn('orders', 'commission_validated_at')) {
                $table->dropColumn('commission_validated_at');
            }

            if (Schema::hasColumn('orders', 'commission_status')) {
                $table->dropColumn('commission_status');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'disabled_at')) {
                $table->dropColumn('disabled_at');
            }

            if (Schema::hasColumn('users', 'suspension_reason')) {
                $table->dropColumn('suspension_reason');
            }

            if (Schema::hasColumn('users', 'suspended_until')) {
                $table->dropColumn('suspended_until');
            }

            if (Schema::hasColumn('users', 'account_status')) {
                $table->dropColumn('account_status');
            }
        });
    }
};