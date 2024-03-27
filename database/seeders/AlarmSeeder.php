<?php

namespace Database\Seeders;

use App\Models\Alarm\PanelSignalCode;
use App\Models\Alarm\Status;
use App\Models\Alarm\Type;
use App\Models\Localizations\Alarm\StatusLocalization;
use Illuminate\Database\Seeder;

class AlarmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->panelSignalCodes();
        $this->alarmTypes();
        $this->alarmStatuses();
    }

    /**
     * Seed Panel Signal Codes
     */
    private function panelSignalCodes()
    {
        $signal_codes = config('seed.alarm.panel_signal_codes');

        foreach ($signal_codes as $seed_item) {
            $signal_code = PanelSignalCode::create([
                'code' => $seed_item['code'],
                'slug' => $seed_item['slug'],
                'trigger_alarm' => $seed_item['trigger_alarm'],
            ]);

            foreach ($seed_item['locales'] as $locale => $data) {
                $signal_code->locales()->create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'locale' => $locale
                ]);
            }
        }
    }

    /**
     * Seed Alarm Types
     */
    private function alarmTypes()
    {
        $alarm_types = config('seed.alarm.alarm_types');

        foreach ($alarm_types as $seed_item) {
            $alarm_type = Type::create([
                'slug' => $seed_item['slug']
            ]);

            foreach ($seed_item['locales'] as $locale => $data) {
                $alarm_type->locales()->create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'locale' => $locale
                ]);
            }
        }
    }

    /**
     * Seed Alarm Statuses
     */
    private function alarmStatuses()
    {
        $alarm_statuses = config('seed.alarm.alarm_statuses');

        foreach ($alarm_statuses as $seed_item) {
            $alarm_status = Status::create([
                'code' => $seed_item['code'],
                'slug' => $seed_item['slug']
            ]);

            foreach ($seed_item['locales'] as $locale => $data) {
                $alarm_status->locales()->create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'locale' => $locale
                ]);
            }
        }
    }
}
