<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function create(){
        return view('admin.brands.create');
    }

    public function index(Request $request){
        $brands = Brands::latest();

        if(!empty($request->get('keywords'))){
            $brands = $brands->where('name','like','%'.$request->get('keywords').'%');
        }

        $brands = $brands->paginate(10);
        return view('admin.brands.list',compact('brands'));
    }    

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if($validator->passes()) {
            $brands = new Brands();
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success','Brands added successfully');
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

    public function edit($brandID, Request $request){

        $brands = Brands::find($brandID);
        if (empty($brands)) {
            return redirect()->route('brands.index');
        }

        return view('admin.brands.edit',compact('brands'));
    }

    public function update($brandID, Request $request){
        $brand = Brands::find($brandID);

        if (empty($brand)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'messege' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique::brands,' . $brand->id . ',id',
        ]);

        if($validator->passes()) {
            $brands = new Brands();
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success','Brands updated successfully');
            return response()->json([
                'status' => true,
                'messege' => 'Brands updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
