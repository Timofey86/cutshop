<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function (Model $model) {
            $model->makeSlug();
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }

    protected function makeSlug(): void
    {
        if (!$this->{$this->slugColumn()}) {
            $slug = $this->makeUniqueSlug(str($this->{$this->slugFrom()})
                ->slug()
                ->value()
            );

            $this->{$this->slugColumn()} = $this->{$this->slugColumn()} ?? $slug;
        }
    }

    protected function slugColumn(): string
    {
        return 'slug';
    }

    private function makeUniqueSlug($slug)
    {
        $count = 1;
        $originalSlug = $slug;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    private function slugExists($slug): bool
    {
        $query = $this->newQuery()
            ->where(self::slugColumn(), $slug)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->withoutGlobalScopes();

        return $query->exists();
    }

}
