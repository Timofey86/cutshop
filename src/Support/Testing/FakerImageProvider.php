<?php

namespace Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use function base_path;

class FakerImageProvider extends Base
{
    public function loremflick(string $fixturesDir, string $storageDir): string
    {
        $storage = Storage::disk('images');
        if (!$storage->exists($storageDir)) {
            $storage->makeDirectory($storageDir);
        }

        $file = $this->generator->file(
            base_path("tests/Fixtures/images/$fixturesDir"),
            $storage->path($storageDir),
            false
        );

        return '/storage/images/' . trim($storageDir,'/') . '/'. $file;
    }

}
