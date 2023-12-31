<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Domain\Product\Models\Option;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class OptionResource extends ModelResource
{
    protected string $model = Option::class;

    protected string $title = 'Options';

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
