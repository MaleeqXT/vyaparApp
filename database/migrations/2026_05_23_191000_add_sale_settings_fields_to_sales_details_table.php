<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_details', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_details', 'invoice_extra_fields')) {
                $table->json('invoice_extra_fields')->nullable()->after('terms_condition_text');
            }

            if (!Schema::hasColumn('sales_details', 'payment_term_name')) {
                $table->string('payment_term_name')->nullable()->after('invoice_extra_fields');
            }

            if (!Schema::hasColumn('sales_details', 'payment_term_days')) {
                $table->integer('payment_term_days')->nullable()->after('payment_term_name');
            }

            if (!Schema::hasColumn('sales_details', 'additional_charges')) {
                $table->json('additional_charges')->nullable()->after('payment_term_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales_details', function (Blueprint $table) {
            foreach (['additional_charges', 'payment_term_days', 'payment_term_name', 'invoice_extra_fields'] as $column) {
                if (Schema::hasColumn('sales_details', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
