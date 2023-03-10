<?php

namespace App\Console\Commands;

use App\Models\Slot;
use Illuminate\Console\Command;

class SlotCommand extends Command
{
    protected $signature = 'slot:add {year}';

    protected $description = 'Add slots for specified year';

    public function handle(): void
    {
        $year = $this->argument('year');
        $numberOfMonths = 12;
        for ($month = 1; $month <= $numberOfMonths; $month++) {
            $numberOfDays = date('t', mktime(0, 0, 0, $month, 1, $year));
            for ($day = 1; $day <= $numberOfDays; $day++) {
                $m = sprintf('%02d', $month);
                $d = sprintf('%02d', $day);
                Slot::create([
                    'date' => $year . '-' . $m . '-' . $d,
                    'start_at' => '06:00:00',
                    'end_at' => '21:00:00',
                    'ban_start_at' => '12:00:00',
                    'ban_end_at' => '13:00:00',
                ]);
            }
        }
    }
}
