<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Requests\Coupon\NewCouponRequest;
use App\Http\Requests\Coupon\UpdateCouponRequest;
use App\Http\Traits\ResponseTrait;
use Exception;

class CouponController extends Controller
{
	use ResponseTrait;
    public function index(){
        $allCoupon = Coupon::orderBy('id', 'DESC')->paginate(25);
        return view('coupon.index', compact('allCoupon'));
    }

    public function addForm(){
        return view('coupon.add_new');
    }

    public function store(NewCouponRequest $request){
        try {
            $coupon = new Coupon;
            $coupon->name = $request->couponName;
            $coupon->code = $request->couponCode;
            $coupon->discount = $request->discount;
            $coupon->noofbill = $request->noofbill;
            $coupon->numberOfCoupon = $request->numberOfCoupon;
			if(trim($request->startAt))
            $coupon->startAt = date('Y-m-d',strtotime($request->startAt));
			if(trim($request->endAt))
            $coupon->endAt = date('Y-m-d',strtotime($request->endAt));
		
            $coupon->status = $request->status;
            $coupon->userId = encryptor('decrypt', $request->userId);

            if(!!$coupon->save()) return redirect(route(currentUser().'.allCoupon'))->with($this->responseMessage(true, null, 'Coupon created'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.allCoupon'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function editForm($id){
        $coupon = Coupon::find(encryptor('decrypt', $id));
        return view('coupon.edit', compact(['coupon']));
    }

    public function update(UpdateCouponRequest $request){
        try {
            $coupon = Coupon::find(encryptor('decrypt', $request->id));
			$coupon->name = $request->couponName;
            $coupon->code = $request->couponCode;
            $coupon->discount = $request->discount;
            $coupon->noofbill = $request->noofbill;
            $coupon->numberOfCoupon = $request->numberOfCoupon;
			if($request->startAt)
            $coupon->startAt = date('Y-m-d',strtotime($request->startAt));
			if($request->endAt)
            $coupon->endAt = date('Y-m-d',strtotime($request->endAt));
            $coupon->status = $request->status;
            $coupon->userId = encryptor('decrypt', $request->userId);
			
			if(($request->numberOfCoupon - $coupon->stock)<0){
				return redirect()->back()->with($this->responseMessage(false, 'error', 'Total Coupon cannot be less then '.$coupon->stock));
				exit;
			}
            if(!!$coupon->save()) return redirect(route(currentUser().'.allCoupon'))->with($this->responseMessage(true, null, 'Coupon updated'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.allCoupon'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

    public function delete($id){
        try {
            $coupon = Coupon::find(encryptor('decrypt', $id));
            if(!!$coupon->delete()){
                return redirect()->back()->with($this->responseMessage(true, null, 'Coupon deleted'));
            }
        }catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
}
