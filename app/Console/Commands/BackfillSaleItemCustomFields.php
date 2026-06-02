<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillSaleItemCustomFields extends Command
{
    protected $signature = 'sales:backfill-item-custom-fields';

    protected $description = 'Backfill sale item custom field snapshots from extra_fields';

    public function handle(): int
    {
        if (!DB::getSchemaBuilder()->hasColumn('sale_items', 'custom_fields')) {
            $this->warn('sale_items.custom_fields column is missing.');
            return self::FAILURE;
        }

        $updated = 0;

        DB::table('sale_items')
            ->select('id', 'custom_fields', 'extra_fields')
            ->orderBy('id')
            ->chunkById(200, function ($rows) use (&$updated) {
                foreach ($rows as $row) {
                    $existing = json_decode((string) ($row->custom_fields ?? '[]'), true);
                    if (is_array($existing) && !empty($existing)) {
                        continue;
                    }

                    $extra = json_decode((string) ($row->extra_fields ?? '{}'), true);
                    if (!is_array($extra)) {
                        continue;
                    }

                    $fields = [];
                    for ($i = 1; $i <= 6; $i++) {
                        $value = trim((string) ($extra['custom_field_' . $i] ?? ''));
                        if ($value === '') {
                            continue;
                        }

                        $fields[] = [
                            'key' => 'custom_field_' . $i,
                            'enabled' => true,
                            'label' => 'Custom Field ' . $i,
                            'show_in_print' => true,
                            'value' => $value,
                        ];
                    }

                    if (empty($fields)) {
                        continue;
                    }

                    DB::table('sale_items')
                        ->where('id', $row->id)
                        ->update(['custom_fields' => json_encode($fields)]);

                    $updated++;
                }
            });

        $this->info('Updated ' . $updated . ' sale item rows.');

        return self::SUCCESS;
    }
}
