<?php

namespace Tests\Feature;

use App\Exceptions\BanTimeBookingException;
use App\Exceptions\InvalidTimeBookingException;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlotTest extends TestCase
{
    use RefreshDatabase;

    private Facility $facility;

    public function setUp(): void
    {
        parent::setUp();

        $this->facility = new Facility();
        $this->facility->name = 'A1会議室';
        $this->facility->save();
    }

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

    /**
     * @test
     */
    public function 早すぎる時間帯に予約を登録しようとすると例外が投げられる(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidTimeBookingException::class);

        $this->artisan('slot:add ' . 2023)->assertExitCode(0);

        $reservation = [
            'user_name' => '鈴木 花子',
            'user_email' => 'suzukihanako@example.com',
            'purpose' => '期末試験',
            'start_at' => '2023-03-01 05:00:00',
            'end_at' => '2023-03-01 06:00:00',
            'note' => '応用数学（佐藤先生）',
        ];
        $response = $this->post('/facilities/' . $this->facility->id . '/reservations', $reservation);
    }

    /**
     * @test
     */
    public function 遅すぎる時間帯に予約を登録しようとしても例外が投げられる(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidTimeBookingException::class);

        $this->artisan('slot:add ' . 2023)->assertExitCode(0);

        $reservation = [
            'user_name' => '鈴木 花子',
            'user_email' => 'suzukihanako@example.com',
            'purpose' => '期末試験',
            'start_at' => '2023-03-01 21:00:00',
            'end_at' => '2023-03-01 22:00:00',
            'note' => '応用数学（佐藤先生）',
        ];
        $response = $this->post('/facilities/' . $this->facility->id . '/reservations', $reservation);
    }

    /**
     * @test
     */
    public function 利用禁止の時間帯に予約を登録しようすると例外が投げられる(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(BanTimeBookingException::class);

        $this->artisan('slot:add ' . 2023)->assertExitCode(0);

        $reservation = [
            'user_name' => '鈴木 花子',
            'user_email' => 'suzukihanako@example.com',
            'purpose' => '期末試験',
            'start_at' => '2023-03-01 12:00:00',
            'end_at' => '2023-03-01 13:00:00',
            'note' => '応用数学（佐藤先生）',
        ];
        $response = $this->post('/facilities/' . $this->facility->id . '/reservations', $reservation);
    }
}
