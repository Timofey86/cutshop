<?php

namespace Support\Traits\Models;

use Illuminate\Support\Facades\File;
use function route;

trait HasThumbnail
{
    abstract protected function thumbnailDir(): string;

    public function makeThumbnail(string $size, string $method = 'resize'):string
    {
        $x =  route('thumbnail',[
            'dir' => $this->thumbnailDir(),
            'method' => $method,
            'size' => $size,
            'file' => File::basename($this->{$this->thumbnailColumn()})
        ]);
        $file = File::basename($this->{$this->thumbnailColumn()});
        $dir = $this->thumbnailDir();
        $y=0;
        return $x;
    }

    protected function thumbnailColumn(): string
    {
        return 'thumbnail';
    }
}
