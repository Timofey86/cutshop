<?php

namespace App\Http\Controllers;

use App\View\ViewModels\CatalogViewModel;
use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): CatalogViewModel
    {
//        $brands = Brand::query()
//            ->select(['id', 'title'])
//            ->has('products')
//            ->get();

//        $categories = Category::query()
//            ->select(['id', 'title', 'slug'])
//            ->has('products')
//            ->get();
//
//        $products = Product::query()
//            ->select(['id', 'title', 'slug', 'price', 'thumbnail', 'json_properties'])
//            ->search()
//            ->withCategory($category)
//            ->filtered()
//            ->sorted()
//            ->paginate(6);

        return(new CatalogViewModel($category))
            ->view('catalog.index');
    }

}
