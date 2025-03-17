<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use App\Models\Product;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest();
        if (!empty($request->get('keywords'))) {
            $products = $products->where('name', 'like', "%" . $request->get('keywords') . "%");
        }
        $products = $products->paginate(10);
        return view('admin.products.list', compact('products'));
    }

    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brands::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;

            $product->save();

            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    //large image
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $destPath = public_path() . '/uploads/product/large/' . $tempImageInfo->name;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);

                    //small image
                    $destPath = public_path() . '/uploads/product/small/' . $tempImageInfo->name;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);
                }
            }
            $request->session()->flash('success', 'Product added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product saved successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($productID, Request $request)
    {
        $product = Product::find($productID);

        if (empty($product)) {
            $request->session()->flash('error', 'Products Not found');
            return response()->json([
                'status' => false,
                'messege' => 'Product not found'
            ], 404);
        }

        File::delete(public_path() . '/uploads/category/' . $product->image);
        $product->delete();

        $request->session()->flash('success', 'Products deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }

    public function edit(Request $request, $productID)
    {
        $product = Product::find($productID);
        $categories = Category::orderBy('name', 'ASC')->get();
        $subCategories = SubCategory::orderBy('name', 'ASC')->get();
        $brands = Brands::orderBy('name', 'ASC')->get();
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }

        $data['product'] = $product;
        $data['categories'] = $categories;
        $data['subCategories'] = $subCategories;
        $data['brands'] = $brands;

        return view('admin.products.edit', $data);
    }


    public function update($productID, Request $request)
    {
        $product = Product::find($productID);

        if(empty($product)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Product not found'
            ]);
        }

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id,
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;

            $product->save();

            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    //large image
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $destPath = public_path() . '/uploads/product/large/' . $tempImageInfo->name;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);

                    //small image
                    $destPath = public_path() . '/uploads/product/small/' . $tempImageInfo->name;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);
                }
            }
            $request->session()->flash('success', 'Product Updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
