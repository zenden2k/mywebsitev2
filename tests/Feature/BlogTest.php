<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/ru/blog');

        $response->assertStatus(200);
    }
}
