<?php

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FakerImageProvider extends Base
{
    public function loremflick(): string
    {
        $name = rand(1, 9). '.jpg';
        $image = base_path('/tests/Fixtures/images/products') . '/'  . $name ;
        $newName = Str::random(6) . '.jpg';
        Storage::disk('public')->makeDirectory('images/products/');
        copy($image, storage_path('/app/public/images/products'). '/'. $newName);
        //Storage::copy($image, '/images/products' . '/' . $newName);
        return '/storage/app/public/images/products/' . $newName;
    }

}
