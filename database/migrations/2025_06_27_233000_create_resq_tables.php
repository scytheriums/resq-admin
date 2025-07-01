<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        
        // Drivers Table
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('status')->default('available');
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->timestamps();
        });

        // Ambulance Types Table
        Schema::create('ambulance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('base_price', 10, 2);
            $table->timestamps();
        });

        // Purposes Table
        Schema::create('purposes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Additional Services Table
        Schema::create('additional_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        // Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('status')->default('created');
            $table->string('payment_status')->default('booking_fee_pending');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('driver_id')->nullable()->constrained();
            $table->foreignId('ambulance_type_id')->constrained();
            $table->foreignId('purpose_id')->constrained();
            $table->json('pickup_location');
            $table->json('destination_location');
            $table->text('notes')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('booking_fee', 10, 2);
            $table->decimal('additional_services_fee', 10, 2);
            $table->decimal('total_bill', 10, 2);
            $table->timestamps();
        });

        // Order Additional Services Table (Pivot)
        Schema::create('order_additional_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('additional_service_id')->constrained();
            $table->timestamps();
        });

        // Activity Logs Table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('action');
            $table->json('data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('order_additional_services');
        Schema::dropIfExists('additional_services');
        Schema::dropIfExists('purposes');
        Schema::dropIfExists('ambulance_types');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('orders');
    }
};
