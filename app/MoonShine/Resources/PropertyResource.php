<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Domain\Product\Models\Property;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class PropertyResource extends ModelResource
{
    protected string $model = Property::class;

    protected string $title = 'Properties';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Title'),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
