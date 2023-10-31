<?php

namespace Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use function base_path;

class FakerImageProvider extends Base
{
    public function loremflick(string $fixturesDir, string $storageDir): string
    {
        if (!Storage::exists('public'.'/'.$storageDir)) {
            Storage::makeDirectory('public'.'/'.$storageDir);
        }

        $file = $this->generator->file(
            base_path("tests/Fixtures/images/$fixturesDir"),
            Storage::path('public/'.$storageDir),
            false
        );

        return /*'/storage/' .*/ trim($storageDir,'/') . '/'. $file;
    }

}
