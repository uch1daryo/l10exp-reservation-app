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

    /**
     * @test
     */
    public function ホーム画面へのリクエストは成功する(): void
    {
        $response = $this->get('/home');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function ルートへのアクセスはホーム画面にリダイレクトされる(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/home');
    }

    /**
     * @test
     */
    public function ホーム画面にはお知らせの年月日が表示される(): void
    {
        $response = $this->get('/home');
        $response->assertSeeTextInOrder(['年', '月', '日']);
    }
}
