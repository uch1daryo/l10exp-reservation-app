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

    public function testCanSeeCalendarThatSpecifiedFacility(): void
    {
        $facility = new Facility();
        $facility->name = 'A1会議室';
        $facility->save();
        $response = $this->get('/facilities/' . $facility->id);
        $response->assertSee('<div id="calendar"></div>', false)
                 ->assertSeeText($facility->name);
    }

    public function testCanSeeNotFoundWhenSpecifiedFacilityDoesNotExist(): void
    {
        $response = $this->get('/facilities/1234567890');
        $response->assertStatus(404);
    }

    public function testCanGetReservationThatSpecifiedFacility(): void
    {
        $facility = new Facility();
        $facility->name = 'A1会議室';
        $facility->save();

        $reservation = new Reservation();
        $reservation->facility_id = $facility->id;
        $reservation->user_name = '山田 太郎';
        $reservation->user_email = 'yamadataro@example.com';
        $reservation->purpose = '打ち合わせ';
        $reservation->start_at = '2023-03-01 09:30:00';
        $reservation->end_at = '2023-03-01 11:00:00';
        $reservation->cancel_code = hash('sha256', spl_object_hash($reservation));
        $reservation->save();

        $response = $this->getJson('/api/facilities/' . $facility->id);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has(1)
                ->first(fn (AssertableJson $json) =>
                    $json->where('id', $reservation->id)
                        ->where('facility_id', $reservation->facility_id)
                        ->where('user_name', $reservation->user_name)
                        ->where('user_email', $reservation->user_email)
                        ->where('purpose', $reservation->purpose)
                        ->where('start_at', $reservation->start_at)
                        ->where('end_at', $reservation->end_at)
                        ->where('cancel_code', $reservation->cancel_code)
                        ->etc()
                )
        );
    }
}
