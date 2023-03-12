<?php

namespace Tests\Feature;

use App\Exceptions\DoubleBookingException;
use App\Mail\ReservationCompletionMail;
use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FacilityTest extends TestCase
{
    use RefreshDatabase;

    private Facility $facility;
    private Reservation $reservation;

    public function setUp(): void
    {
        parent::setUp();

        $this->facility = new Facility();
        $this->facility->name = 'A1会議室';
        $this->facility->save();

        $this->reservation = new Reservation();
        $this->reservation->facility_id = $this->facility->id;
        $this->reservation->user_name = '山田 太郎';
        $this->reservation->user_email = 'yamadataro@example.com';
        $this->reservation->purpose = '打ち合わせ';
        $this->reservation->start_at = '2023-03-01 09:30:00';
        $this->reservation->end_at = '2023-03-01 11:00:00';
        $this->reservation->cancel_code = hash('sha256', spl_object_hash($this->reservation));
        $this->reservation->save();

        $this->artisan('slot:add ' . 2023);
    }

    /**
     * @test
     */
    public function 設備を指定してカレンダーを表示できる(): void
    {
        $response = $this->get('/facilities/' . $this->facility->id . '/reservations');
        $response->assertSee('<div id="calendar"></div>', false)
                 ->assertSeeText($this->facility->name);
    }

    /**
     * @test
     */
    public function 存在しない設備を指定するとカレンダーは表示されない(): void
    {
        $response = $this->get('/facilities/1234567890/reservations');
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function 番号でない設備を指定してもカレンダーは表示されない(): void
    {
        $response = $this->get('/facilities/abcd1234/reservations');
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function 設備を指定して予約情報を取得できる(): void
    {
        $response = $this->getJson('/api/facilities/' . $this->facility->id . '/reservations');
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has(1)
                ->first(fn (AssertableJson $json) =>
                    $json->where('id', $this->reservation->id)
                        ->where('title', $this->reservation->purpose)
                        ->where('start', $this->reservation->start_at)
                        ->where('end', $this->reservation->end_at)
                        ->where('description', $this->reservation->user_name)
                )
        );
    }

    /**
     * @test
     */
    public function 指定した設備の予約の登録画面を表示できる(): void
    {
        $response = $this->get('/facilities/' . $this->facility->id . '/reservations/create');
        $response->assertSeeText('登録する');
    }

    /**
     * @test
     */
    public function 指定した設備の予約を登録できる(): void
    {
        $reservation = [
            'user_name' => '鈴木 花子',
            'user_email' => 'suzukihanako@example.com',
            'purpose' => '期末試験',
            'start_at' => '2023-03-02 09:00:00',
            'end_at' => '2023-03-02 12:00:00',
            'note' => '応用数学（佐藤先生）',
        ];
        $response = $this->post('/facilities/' . $this->facility->id . '/reservations', $reservation);
        $this->assertDatabaseHas('reservations', $reservation);
    }

    /**
     * @test
     */
    public function 設備の予約を完了するとお知らせメールが届く(): void
    {
        Mail::fake();
        Mail::assertNothingQueued();
        $reservation = [
            'user_name' => '鈴木 花子',
            'user_email' => 'suzukihanako@example.com',
            'purpose' => '期末試験',
            'start_at' => '2023-03-12 09:00:00',
            'end_at' => '2023-03-12 12:00:00',
            'note' => '応用数学（佐藤先生）',
        ];
        $response = $this->post('/facilities/' . $this->facility->id . '/reservations', $reservation);
        Mail::assertQueued(ReservationCompletionMail::class);
    }

    /**
     * @test
     */
    public function 不完全な予約を登録しようとするとリダイレクトされる(): void
    {
        $reservation = [];
        $response = $this->post('/facilities/' . $this->facility->id . '/reservations', $reservation);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function 重複して予約を登録しようとすると例外が投げられる(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(DoubleBookingException::class);

        $reservation = [
            'user_name' => '鈴木 花子',
            'user_email' => 'suzukihanako@example.com',
            'purpose' => '期末試験',
            'start_at' => '2023-03-01 09:00:00',
            'end_at' => '2023-03-01 12:00:00',
            'note' => '応用数学（佐藤先生）',
        ];
        $response = $this->post('/facilities/' . $this->facility->id . '/reservations', $reservation);
    }

    /**
     * @test
     */
    public function 指定した予約のキャンセル画面を表示できる(): void
    {
        $response = $this->get('/facilities/' . $this->facility->id . '/reservations/' . $this->reservation->cancel_code);
        $response->assertSeeText('キャンセルする');
    }

    /**
     * @test
     */
    public function 指定した予約をキャンセルできる(): void
    {
        $response = $this->delete('/facilities/' . $this->facility->id . '/reservations/' . $this->reservation->cancel_code);
        $this->assertDatabaseMissing('reservations', [
            'user_name' => '山田 太郎',
            'user_email' => 'yamadataro@example.com',
            'purpose' => '打ち合わせ',
            'start_at' => '2023-03-01 09:30:00',
            'end_at' => '2023-03-01 11:00:00',
        ]);
    }
}
