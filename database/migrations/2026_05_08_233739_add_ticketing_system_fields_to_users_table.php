<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add all needed columns at once
            $table->string('phone')->nullable()->after('email');
            $table->string('national_id')->nullable()->after('phone');
            $table->enum('role', ['admin', 'event_manager', 'customer'])->default('customer')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->softDeletes();
            
            // Add indexes
            $table->index(['role', 'is_active']);
            $table->index('phone');
            $table->index('national_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'national_id',
                'role',
                'is_active',
                'last_login_at',
                'password_changed_at',
                'two_factor_secret',
                'two_factor_enabled'
            ]);
            $table->dropSoftDeletes();
            $table->dropIndex(['role', 'is_active']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['national_id']);
        });
    }
};