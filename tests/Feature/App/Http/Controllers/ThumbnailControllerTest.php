<?php

namespace App\Http\Controllers;

use Database\Factories\ProductFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Tests\TestCase;

class ThumbnailControllerTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_thumbnail_generate_success(): void
    {
        $method = 'resize';
        $size = '500x500';
        $storage = Storage::disk('images');

       config()->set('thumbnail',['allowed_size' => [$size]]);

       $product = ProductFactory::new()->create();

        $response = $this->get($product->makeThumbnail($size,$method));

        //Mocking Image
//        Image::shouldReceive('make')
//        ->once()
//        ->andReturnSelf()
//        ->shouldReceive('resize')
//        ->once()
//        ->shouldReceive('save')
//        ->once()
//        ->andReturn();
        $response->assertOk();
        $storage->assertExists("products/$method/$size/". File::basename($product->thumbnail));
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
