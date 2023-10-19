<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static:: creating(function (Model $model) {
            $slug = Str::slug($model->{self::slugFrom()});
            $model->slug = static::makeUniqueSlug($slug);
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }

    protected static function makeUniqueSlug($slug)
    {
        $count = 1;
        $originalSlug = $slug;

        while (static::slugExists($slug)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    protected static function slugExists($slug)
    {
        return static::where('slug', $slug)->exists();
    }

}
