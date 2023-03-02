<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

}
