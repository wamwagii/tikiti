<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->string('payment_status')->nullable()->after('payment_reference');
            $table->json('payment_data')->nullable()->after('payment_status');
            
            // Add indexes
            $table->index('payment_reference');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_reference', 'payment_status', 'payment_data']);
            $table->dropIndex(['payment_reference']);
            $table->dropIndex(['payment_status']);
        });
    }
};