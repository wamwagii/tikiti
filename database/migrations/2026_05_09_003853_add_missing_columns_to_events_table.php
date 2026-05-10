<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('events', 'title')) {
                $table->string('title')->after('id');
            }
            if (!Schema::hasColumn('events', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('title');
            }
            if (!Schema::hasColumn('events', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('events', 'type')) {
                $table->enum('type', ['football', 'concert', 'other'])->default('football')->after('description');
            }
            if (!Schema::hasColumn('events', 'venue_id')) {
                $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete()->after('type');
            }
            if (!Schema::hasColumn('events', 'start_date')) {
                $table->dateTime('start_date')->nullable()->after('venue_id');
            }
            if (!Schema::hasColumn('events', 'end_date')) {
                $table->dateTime('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('events', 'image')) {
                $table->string('image')->nullable()->after('end_date');
            }
            if (!Schema::hasColumn('events', 'base_price')) {
                $table->decimal('base_price', 10, 2)->default(0)->after('image');
            }
            if (!Schema::hasColumn('events', 'status')) {
                $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft')->after('base_price');
            }
            if (!Schema::hasColumn('events', 'featured')) {
                $table->boolean('featured')->default(false)->after('status');
            }
            if (!Schema::hasColumn('events', 'ticket_tiers')) {
                $table->json('ticket_tiers')->nullable()->after('featured');
            }
            if (!Schema::hasColumn('events', 'total_tickets')) {
                $table->integer('total_tickets')->default(0)->after('ticket_tiers');
            }
            if (!Schema::hasColumn('events', 'tickets_sold')) {
                $table->integer('tickets_sold')->default(0)->after('total_tickets');
            }
            if (!Schema::hasColumn('events', 'tickets_available')) {
                $table->integer('tickets_available')->default(0)->after('tickets_sold');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'slug', 'description', 'type', 'venue_id', 'start_date',
                'end_date', 'image', 'base_price', 'status', 'featured',
                'ticket_tiers', 'total_tickets', 'tickets_sold', 'tickets_available'
            ]);
        });
    }
};