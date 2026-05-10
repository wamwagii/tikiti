<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('venues', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('venues', 'location')) {
                $table->string('location')->nullable()->after('name');
            }
            if (!Schema::hasColumn('venues', 'city')) {
                $table->string('city')->default('Nairobi')->after('location');
            }
            if (!Schema::hasColumn('venues', 'address')) {
                $table->string('address')->nullable()->after('city');
            }
            if (!Schema::hasColumn('venues', 'capacity')) {
                $table->integer('capacity')->nullable()->after('address');
            }
            if (!Schema::hasColumn('venues', 'description')) {
                $table->text('description')->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('venues', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (!Schema::hasColumn('venues', 'amenities')) {
                $table->json('amenities')->nullable()->after('image');
            }
            if (!Schema::hasColumn('venues', 'contact_info')) {
                $table->json('contact_info')->nullable()->after('amenities');
            }
            if (!Schema::hasColumn('venues', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('contact_info');
            }
        });
    }

    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'location', 'city', 'address', 'capacity',
                'description', 'image', 'amenities', 'contact_info', 'is_active'
            ]);
        });
    }
};