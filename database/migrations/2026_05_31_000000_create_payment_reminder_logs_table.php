<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('party_id')->nullable()->constrained()->nullOnDelete();
            $table->string('party_name');
            $table->string('phone', 30)->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedInteger('overdue_days')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('reminder_type', 30);
            $table->string('status', 20)->default('pending');
            $table->string('provider', 50)->nullable();
            $table->string('provider_message_id')->nullable();
            $table->longText('message')->nullable();
            $table->longText('provider_response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['sale_id', 'sent_at']);
            $table->index(['party_id', 'sent_at']);
            $table->index(['status', 'reminder_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_reminder_logs');
    }
};
