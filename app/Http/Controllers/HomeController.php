<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
    public function __invoke(): Factory|Application|View
    {
        $categories = CategoryViewModel::make()->homePage();

        $brands = BrandViewModel::make()->homePage();

        $products = Product::query()->homePage()->get();

        return view('index', compact('products','brands', 'categories'));
    }
}
