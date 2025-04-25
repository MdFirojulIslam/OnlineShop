<?php

use App\Models\Brands;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;

const STATUS_ACTIVE = 1;
const STATUS_INACTIVE = 0;

function getCategories()
{
    return Category::orderBy('name', 'ASC')
        ->with('sub_category')
        ->orderBy('id','DESC')
        ->where('status', 1)
        ->where('showHome', 'Yes')
        ->get(); 
}

function getStatusCount($model){
    return [
        'active' => $model::where('status',STATUS_ACTIVE)->count(),
        'inactive' => $model::where('status',STATUS_INACTIVE)->count(),
        'total'=> $model::count()
    ];
}

function getProductsCount(){
    return getStatusCount(Product::class);
}

function getCategoriesCount(){
    return getStatusCount(Category::class);
}

function getSubCategoriesCount(){
    return getStatusCount(SubCategory::class);    
}

function getBrandsCount(){
    return getStatusCount(Brands::class);
}
