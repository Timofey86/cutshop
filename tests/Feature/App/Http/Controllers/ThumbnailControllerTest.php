<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThumbnailControllerTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_thumbnail_generate_success(): void
    {
        $dir = 'brands';
        $method = 'resize';
        $size = '70x70';
        $file = 'product_1.jpg';

        Storage::fake('images');

        $image = UploadedFile::fake()->image($file, 200, 200);

        Storage::disk('images')->putFileAs($dir, $image, $file);

        $response = $this->get(action(
                ThumbnailController::class, [
                    'dir' => $dir,
                    'method' => $method,
                    'size' => $size,
                    'file' => $file
                ]
            )
        );

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'image/jpeg');
    }

    /**
     * @test
     * @return void
     */
    public function it_thumbnail_invalid_size(): void
    {
        $dir = 'brands';
        $method = 'resize';
        $size = '220x220';
        $file = 'product_1.jpg';

        $response = $this->get(action(
                ThumbnailController::class, [
                    'dir' => $dir,
                    'method' => $method,
                    'size' => $size,
                    'file' => $file
                ]
            )
        );

        $response->assertStatus(403);
    }
}
