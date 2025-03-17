<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Image;


class CategoryController extends Controller
{
    public function index(Request $request)
    { 
        $categories = Category::latest();

        if (!empty($request->get('keywords'))) {
            $categories = $categories->where('name', 'like', "%" . $request->get('keywords') . "%");
        }
        $categories = $categories->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);
        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            //Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $img = Image::make($sPath);
                $img->resize(450,600);
                $img->save($dPath);


                $category->image = $newImageName;
                $category->save();
            }

            $request->session()->flash('success', 'Category added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($categoryID, Request $request)
    {
        $category = Category::find($categoryID);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }

    public function update($categoryID, Request $request)
    {
        $category = Category::find($categoryID);
        
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id,
        ]);
        
        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;
            //Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '_' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $img = Image::make($sPath);
                $img->resize(450,600);
                $img->save($dPath);


                $category->image = $newImageName;
                $category->save();

                //delelete old images here
                File::delete(public_path() . '/uploads/category/' . $oldImage);
            }

            $request->session()->flash('success', 'Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($categoryID, Request $request) {
        $category = Category::find($categoryID);

        if (empty($category)) {
            $request->session()->flash('error', 'Category Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        if (!empty($category->image)){
            File::delete(public_path() . '/uploads/category/' . $category->image);
        }

        $category->delete();
        $request->session()->flash('success', 'Products deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ], 200);
    }

}
