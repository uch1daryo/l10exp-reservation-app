<?php

namespace Tests\Feature;

use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
