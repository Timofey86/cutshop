<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Brand;
use Illuminate\Support\Collection;
use Support\Traits\Makeable;

class BrandViewModel
{
    use Makeable;

    public function homePage(): Collection|array
    {
        //todo Observer
//       return Cache::rememberForever('category_home_page', function (){
        return Brand::query()->homePage()->get();
//        });
    }

}
