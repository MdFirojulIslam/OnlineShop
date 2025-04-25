<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;


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
            'status' => 'nullable',
            'showHome' => 'nullable|in:Yes,No',
            'image_id' => 'nullable|integer|exists:temp_images,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showHome = $request->showHome;
        $category->save();

        if ($request->image_id) {

            $tempImage = TempImage::find($request->image_id);

            if ($tempImage) {
                $ext = pathinfo($tempImage->name, PATHINFO_EXTENSION);
                $newImageName = $category->id . '_' . time() . '.' . $ext;
                $sPath = public_path('temp/' . $tempImage->name);
                $dPath = public_path('uploads/category/' . $newImageName);
                $thumbPath = public_path('uploads/category/thumb/' . $newImageName);

                if (File::exists($sPath)) {
                    File::copy($sPath, $dPath);

                    // Create Thumbnail
                    $manager = new ImageManager(new GdDriver());
                    $img = $manager->read($sPath)->cover(450, 600);
                    $img->save($thumbPath);

                    $category->image = $newImageName;
                    $category->save();
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Category added successfully'
        ]);
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

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showHome = $request->showHome;
        $oldImage = $category->image;

        if (!empty($request->image_id)) {
            $tempImage = TempImage::find($request->image_id);

            if ($tempImage) {
                $ext = pathinfo($tempImage->name, PATHINFO_EXTENSION);
                $newImageName = $category->id . '_' . time() . '.' . $ext;

                $sPath = public_path('temp/' . $tempImage->name);
                $dPath = public_path('uploads/category/' . $newImageName);
                $thumbPath = public_path('uploads/category/thumb/' . $newImageName);

                if (File::exists($sPath)) {
                    // Copy original image
                    File::copy($sPath, $dPath);

                    $manager = new ImageManager(new GdDriver());
                    $img = $manager->read($sPath)->cover(450, 600); // Or use ->resize() if needed
                    $img->save($thumbPath);

                    // Update DB
                    $category->image = $newImageName;
                    $category->save();

                    // Delete old image
                    if (!empty($oldImage)) {
                        File::delete(public_path('uploads/category/' . $oldImage));
                        File::delete(public_path('uploads/category/thumb/' . $oldImage));
                    }
                }
            }
        }

        $category->save();

        $request->session()->flash('success', 'Category updated successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully'
        ]);
    }

    public function destroy($categoryID, Request $request)
    {
        $category = Category::find($categoryID);

        if (empty($category)) {
            $request->session()->flash('error', 'Category Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        if (!empty($category->image)) {
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
