<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\Number;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class CategoryResource extends ModelResource
{
    protected string $model = Category::class;

    protected string $title = 'Categories';


    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Title')
                    ->updateOnPreview()
                    ->required(),

                Slug::make('Slug')
                    ->from('title')
                    ->separator('-'),

                Number::make('Sorting')
                    ->buttons()
                    ->default(0)
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
