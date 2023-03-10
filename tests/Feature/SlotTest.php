<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlotTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function スロットを作るコマンドで一年分スロットを作ることができる(): void
    {
        $year = random_int(2023, 2033);
        $numberOfDaysInYear = date('L', mktime(0, 0, 0, 1, 1, $year)) ? 366 : 365;

        $this->artisan('slot:add ' . $year)->assertExitCode(0);
        $this->assertDatabaseCount('slots', $numberOfDaysInYear);
    }
}
