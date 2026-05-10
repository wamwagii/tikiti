<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('ticket_number')->unique();
            $table->string('tier_name'); // VIP, VVIP, Regular, etc.
            $table->decimal('price', 10, 2);
            $table->string('seat_number')->nullable();
            $table->string('qr_code')->nullable();
            $table->enum('status', ['available', 'sold', 'used', 'refunded'])->default('available');
            $table->json('attendee_details')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};