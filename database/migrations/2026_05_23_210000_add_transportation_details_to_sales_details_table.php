<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_details', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_details', 'transportation_details')) {
                $table->json('transportation_details')->nullable()->after('additional_charges');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales_details', function (Blueprint $table) {
            if (Schema::hasColumn('sales_details', 'transportation_details')) {
                $table->dropColumn('transportation_details');
            }
        });
    }
};
