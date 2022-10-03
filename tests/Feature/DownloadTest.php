<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DownloadTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/downloads/image-uploader-latest');

        $response->assertRedirect('https://bintray.com/artifact/download/zenden/zenden-image-uploader/image-uploader-1.3.1-build-4318-setup.exe');
    }

    public function test_invalid()
    {
        $response = $this->get('/downloads/dfjohguoidfhgjlkdfhigj');

        $response->assertNotFound();
    }
}
