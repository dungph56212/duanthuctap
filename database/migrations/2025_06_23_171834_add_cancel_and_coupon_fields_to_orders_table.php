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
            $table->timestamp('cancelled_at')->nullable()->after('delivered_at');
            $table->string('cancelled_reason')->nullable()->after('cancelled_at');
            $table->foreignId('coupon_id')->nullable()->after('cancelled_reason')->constrained('coupons')->onDelete('set null');
            $table->decimal('coupon_discount', 10, 2)->default(0)->after('coupon_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['cancelled_at', 'cancelled_reason', 'coupon_id', 'coupon_discount']);
        });
    }
};
