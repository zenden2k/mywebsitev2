<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class TabTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_tab_index()
    {
        $user = User::factory()->create();
        $user->is_admin = 1;

        $response = $this->actingAs($user)
            ->get('/api/tab');

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('success', 'data', 'message')
                ->whereAllType([
                    'success' => 'boolean',
                    'data' => 'array',
                    'message' => 'string'
                ])
                ->has('data.data.0', fn (AssertableJson $json) =>
                    $json->where('id', 1)
                        ->hasAll('title_ru', 'title_en', 'url', 'orderNumber')
                        ->whereAllType([
                            'title_ru' => 'string',
                            'title_en' => 'string',
                            'url' => 'string',
                            'orderNumber' => 'integer'
                        ])
                        ->etc())
                ->etc());
    }
}
