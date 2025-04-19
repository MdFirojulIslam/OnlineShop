<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\DiscountCoupon;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(){
        return view('admin.coupons.list');
    }
    
    public function create(){
        return view('admin.coupon.create');
    }
    
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code' =>'required',
            'type' =>'required',
            'discount_amount' => 'required',
            'status' => 'required',
        ]);
        if ($validator->passes()) {
            if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormate('Y-m-d H:i:s', $request->starts_at);

                if($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start date can not be less than current date time']
                    ]);
                }
            }
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormate('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormate('Y-m-d H:i:s', $request->starts_at);

                if($expiresAt->gt($startAt) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expire date must be greater than start date']
                    ]);
                }
            }
            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->descrition;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_ar = $request->starts_ar;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $message = 'Discount coupon added successfully.';
            
            session()->flash('success',$message);

            response()->json([
                'status' => true,
                'errors' => $message
            ]);

        } else {
            response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
    public function edit(){
        
    }
    
    public function update(){
        
    }
    
    public function destroy(){
        
    }

}
