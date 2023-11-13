<?php

namespace App\Models;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pipeline\Pipeline;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Support\Casts\PriceCast;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;
//    use Searchable;

    protected $fillable = [
        'slug',
        'title',
        'brand_id',
        'price',
        'thumbnail',
        'on_home_page',
        'sorting',
        'text'
    ];

    protected $casts = [
        'price' => PriceCast::class
    ];

    protected function thumbnailDir(): string
    {
        return 'products';
    }

//    #[SearchUsingPrefix(['id'])]
//    #[SearchUsingFullText(['title', 'text'])]
//    public function toSearchableArray(): array
//    {
//        return [
////            'id' => $this->id,
//            'title' => $this->title,
//            'text' => $this->text,
////            'email' => $this->email,
////            'bio' => $this->bio,
//        ];
//    }

    public function scopeFiltered(Builder $query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through(filters())
            ->thenReturn();
//        foreach (filters() as $filter) {
//            $query = $filter->apply($query);
//        }
    }

    public function scopeSorted(Builder $query)
    {
        $query->when(request('sort'), function (Builder $q){
            $column = request()->str('sort');
            if ($column->contains(['price', 'title'])) {
                $direction = $column->contains('-') ? "DESC" : "ASC";
                $q->orderBy((string) $column->remove('-'), $direction);
            }
        });
    }

    public function scopeHomePage(Builder $query)
    {
        $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class)
            ->withPivot('value');
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
    }
}
