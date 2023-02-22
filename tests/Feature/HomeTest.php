<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
    }

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

    public function testCanSeeDateInHome(): void
    {
        $response = $this->get('/home');
        $response->assertSeeTextInOrder(['年', '月', '日']);
    }
}
