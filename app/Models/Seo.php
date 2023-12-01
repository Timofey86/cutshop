<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Support\Casts\SeoUrlCast;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url'
    ];

    protected $casts = [
        'url' => SeoUrlCast::class
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (Seo $model) {
            Cache::forget('seo_' . str($model->url)->slug('_'));
        });

        static::updated(function (Seo $model) {
            Cache::forget('seo_' . str($model->url)->slug('_'));
        });

        static::deleted(function (Seo $model) {
            Cache::forget('seo_' . str($model->url)->slug('_'));
        });
    }
}
