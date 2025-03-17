<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function create()
    {
        return view('admin.brands.create');
    }

    public function index(Request $request)
    {
        $brands = Brands::latest();

        if (!empty($request->get('keywords'))) {
            $brands = $brands->where('name', 'like', '%' . $request->get('keywords') . '%');
        }

        $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if ($validator->passes()) {
            $brands = new Brands();
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success', 'Brands added successfully');
            return response()->json([
                'status' => true,
                'messege' => 'Brands added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($brandID, Request $request)
    {
        $brands = Brands::find($brandID);
        if (empty($brands)) {
            return redirect()->route('brands.index');
        }

        return view('admin.brands.edit', compact('brands'));
    }

    public function update($brandID, Request $request)
    {
        $brand = Brands::find($brandID);

        if (empty($brand)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brand not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id,
        ]);

        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function destroy($brandID, Request $request)
    {
        $brand = Brands::find($brandID);

        if (empty($brand)) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }

        $brand->delete();
        $request->session()->flash('success', 'Brand deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ], 200);
    }
}
