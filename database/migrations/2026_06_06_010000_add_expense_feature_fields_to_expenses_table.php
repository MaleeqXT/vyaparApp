<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'party_id')) {
                $table->foreignId('party_id')->nullable()->after('expense_category_id')->constrained('parties')->nullOnDelete();
            }

            if (!Schema::hasColumn('expenses', 'tax_enabled')) {
                $table->boolean('tax_enabled')->default(false)->after('party');
            }

            if (!Schema::hasColumn('expenses', 'tax_rate_id')) {
                $table->foreignId('tax_rate_id')->nullable()->after('tax_enabled')->constrained('tax_rates')->nullOnDelete();
            }

            if (!Schema::hasColumn('expenses', 'tax_rate_name')) {
                $table->string('tax_rate_name')->nullable()->after('tax_rate_id');
            }

            if (!Schema::hasColumn('expenses', 'tax_rate_value')) {
                $table->decimal('tax_rate_value', 10, 4)->default(0)->after('tax_rate_name');
            }

            if (!Schema::hasColumn('expenses', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_rate_value');
            }

            if (!Schema::hasColumn('expenses', 'items_json')) {
                $table->json('items_json')->nullable()->after('tax_amount');
            }

            if (!Schema::hasColumn('expenses', 'additional_charges')) {
                $table->json('additional_charges')->nullable()->after('items_json');
            }

            if (!Schema::hasColumn('expenses', 'transportation_details')) {
                $table->json('transportation_details')->nullable()->after('additional_charges');
            }

            if (!Schema::hasColumn('expenses', 'description')) {
                $table->text('description')->nullable()->after('transportation_details');
            }

            if (!Schema::hasColumn('expenses', 'bank_account_id')) {
                $table->foreignId('bank_account_id')->nullable()->after('description')->constrained('bank_accounts')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            foreach ([
                'bank_account_id',
                'description',
                'transportation_details',
                'additional_charges',
                'items_json',
                'tax_amount',
                'tax_rate_value',
                'tax_rate_name',
                'tax_rate_id',
                'tax_enabled',
                'party_id',
            ] as $column) {
                if (Schema::hasColumn('expenses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
