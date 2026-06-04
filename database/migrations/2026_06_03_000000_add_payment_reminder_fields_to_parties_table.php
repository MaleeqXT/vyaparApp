<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parties', function (Blueprint $table) {
            if (!Schema::hasColumn('parties', 'payment_reminder_enabled')) {
                $table->boolean('payment_reminder_enabled')->default(false)->after('party_group');
            }
            if (!Schema::hasColumn('parties', 'payment_reminder_phone')) {
                $table->string('payment_reminder_phone', 30)->nullable()->after('payment_reminder_enabled');
            }
            if (!Schema::hasColumn('parties', 'payment_reminder_date')) {
                $table->date('payment_reminder_date')->nullable()->after('payment_reminder_phone');
            }
            if (!Schema::hasColumn('parties', 'payment_reminder_message')) {
                $table->longText('payment_reminder_message')->nullable()->after('payment_reminder_date');
            }
            if (!Schema::hasColumn('parties', 'payment_reminder_sent_at')) {
                $table->timestamp('payment_reminder_sent_at')->nullable()->after('payment_reminder_message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('parties', function (Blueprint $table) {
            $columns = [
                'payment_reminder_enabled',
                'payment_reminder_phone',
                'payment_reminder_date',
                'payment_reminder_message',
                'payment_reminder_sent_at',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('parties', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
