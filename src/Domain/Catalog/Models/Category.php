<?php

namespace Domain\Catalog\Models;

use App\Models\Product;
use Database\Factories\BrandFactory;
use Domain\Catalog\Collections\CategoryCollection;
use Domain\Catalog\QueryBuilders\CategoryQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use JetBrains\PhpStorm\Pure;
use Support\Traits\Models\HasSlug;

/**
 * @method static Category|CategoryQueryBuilder query()
 */
class Category extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'slug',
        'title',
        'on_home_page',
        'sorting'
    ];

    public function newCollection(array $models = []): CategoryCollection
    {
        return new CategoryCollection($models);
    }

    #[Pure] public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
//
//    protected static function newFactory(): BrandFactory
//    {
//        return BrandFactory::new();
//    }
}