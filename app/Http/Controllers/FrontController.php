<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        $featureProducts = Product::with('productImages')
            ->where('is_featured', 'yes')
            ->where('status', 1)
            ->orderBy('id', 'ASC');

        $latestProducts = Product::with('productImages')
            ->where('status', 1)
            ->orderBy('id', 'ASC');

        if (!empty($request->get('searchForProducts'))) {
            $keywords = $request->get('searchForProducts');
            $featureProducts = $featureProducts->where('title', 'like', "%" . $keywords . "%");
            $latestProducts = $latestProducts->where('title', 'like', "%" . $keywords . "%");
        }

        $featureProducts = $featureProducts->take(8)->get();
        $latestProducts = $latestProducts->take(8)->get();

        $data['featureProducts'] = $featureProducts;
        $data['latestProducts'] = $latestProducts;

        foreach ($featureProducts as $product) {
            $product->image = $product->productImages->first();
        }

       
        foreach ($latestProducts as $product) {
            $product->image = $product->productImages->first();
        }

        return view('front.home', $data);
    }
}
