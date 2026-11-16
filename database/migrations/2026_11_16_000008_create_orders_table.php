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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->enum('order_type', ['dine_in', 'takeaway'])->default('dine_in');
            $table->foreignId('table_id')->nullable()->constrained('tables')->nullOnDelete();
            $table->string('takeaway_number', 20)->nullable();
            $table->string('customer_name', 100);
            $table->unsignedInteger('subtotal')->default(0);
            $table->unsignedInteger('tax_amount')->default(0);
            $table->unsignedInteger('total_amount')->default(0);
            $table->enum('payment_method', ['cash', 'midtrans'])->default('cash');
            $table->enum('payment_status', ['unpaid', 'paid', 'expired', 'failed'])->default('unpaid');
            $table->enum('order_status', ['pending', 'received', 'cooking', 'ready', 'completed', 'cancelled'])
                ->default('pending')
                ->index();
            $table->string('snap_token', 255)->nullable();
            $table->string('midtrans_transaction_id', 100)->nullable();
            $table->string('payment_type_detail', 50)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
