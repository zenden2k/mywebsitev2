<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CallbackTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_main()
    {
        $response = $this->get('/callback?code=testcode&state=token');

        $response->assertViewIs('callback');
        $response->assertStatus(200);

        $response->assertSee('testcode');

        $response2 = $this->get('/callback');

        $response2->assertNotFound();
    }
}
