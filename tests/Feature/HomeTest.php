<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testCanGetHome(): void
    {
        $response = $this->get('/home');
        $response->assertStatus(200);
    }

    public function testCanRedirectFromRootToHome(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/home');
    }
}
