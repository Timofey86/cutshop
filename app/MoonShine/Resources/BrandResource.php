<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\Image;
use MoonShine\Fields\Number;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class BrandResource extends ModelResource
{
    protected string $model = Brand::class;

    protected string $title = 'Brands';

    protected string $column = 'title';



    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Title')
                    ->updateOnPreview()
                    ->showOnExport()
                    ->required(),

                Slug::make('Slug')
                ->from('title')
                ->separator('-'),

                Number::make('Sorting')
                ->buttons()
                ->default(0),
                Image::make('Thumbnail')
                    ->disk('public')
                    ->dir('images/brands'),
                Switcher::make('On home page')
                ->updateOnPreview(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function search(): array
    {
        return ['id', 'title'];
    }
}
