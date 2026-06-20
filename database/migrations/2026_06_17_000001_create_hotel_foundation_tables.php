<?php

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('room_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('base_price', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('room_category_id')->constrained('room_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('room_number')->unique();
            $table->unsignedSmallInteger('floor')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('status')->default(RoomStatus::Available->value)->index();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('facilities', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('facility_room', function (Blueprint $table): void {
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['facility_id', 'room_id']);
        });

        Schema::create('guests', function (Blueprint $table): void {
            $table->id();
            $table->string('identity_number')->unique();
            $table->string('full_name');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable()->index();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reservations', function (Blueprint $table): void {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->unsignedInteger('nights');
            $table->decimal('total_price', 12, 2);
            $table->string('status')->default(ReservationStatus::Reserved->value)->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['room_id', 'check_in_date', 'check_out_date']);
            $table->index(['guest_id', 'status']);
        });

        Schema::create('activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('guests');
        Schema::dropIfExists('facility_room');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_categories');
    }
};
