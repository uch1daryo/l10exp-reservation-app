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

    public function testCanSeeCalendarThatSpecifiedFacility(): void
    {
        $response = $this->get('/facilities/' . $this->facility->id);
        $response->assertSee('<div id="calendar"></div>', false)
                 ->assertSeeText($this->facility->name);
    }

    public function testCanSeeNotFoundWhenSpecifiedFacilityDoesNotExist(): void
    {
        $response = $this->get('/facilities/1234567890');
        $response->assertStatus(404);
    }

    public function testCanGetReservationThatSpecifiedFacility(): void
    {
        $response = $this->getJson('/api/facilities/' . $this->facility->id);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has(1)
                ->first(fn (AssertableJson $json) =>
                    $json->where('id', $this->reservation->id)
                        ->where('facility_id', $this->reservation->facility_id)
                        ->where('user_name', $this->reservation->user_name)
                        ->where('user_email', $this->reservation->user_email)
                        ->where('purpose', $this->reservation->purpose)
                        ->where('start_at', $this->reservation->start_at)
                        ->where('end_at', $this->reservation->end_at)
                        ->where('cancel_code', $this->reservation->cancel_code)
                        ->etc()
                )
        );
    }
}
