<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_details', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_details', 'terms_condition_name')) {
                $table->string('terms_condition_name')->nullable()->after('bilti_gari_no');
            }

            if (!Schema::hasColumn('sales_details', 'terms_condition_text')) {
                $table->text('terms_condition_text')->nullable()->after('terms_condition_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales_details', function (Blueprint $table) {
            if (Schema::hasColumn('sales_details', 'terms_condition_text')) {
                $table->dropColumn('terms_condition_text');
            }

            if (Schema::hasColumn('sales_details', 'terms_condition_name')) {
                $table->dropColumn('terms_condition_name');
            }
        });
    }
};
