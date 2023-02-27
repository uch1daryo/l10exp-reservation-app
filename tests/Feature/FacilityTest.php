<?php

namespace Tests\Feature;

use Tests\TestCase;

class FacilityTest extends TestCase
{
    public function testCanSeeCalendarThatSpecifiedFacility(): void
    {
        $response = $this->get('/facilities/1');
        $response->assertSee('<div id="calendar"></div>', false);
    }
}
