<?php

namespace App\Http\Controllers;

use App\Models\UserPackage;
use Illuminate\Http\Request;
use App\Http\Requests\UserPackage\NewUserPackageRequest;
use App\Http\Requests\UserPackage\UpdateUserPackageRequest;
use App\Http\Traits\ResponseTrait;

use App\Models\Package;
use App\Models\Coupon;
use App\Models\User;

use Exception;
use DB;

class UserPackageController extends Controller
{
	use ResponseTrait;
   
    public function index(){
		if(currentUser()=="superadmin")
			$allUserPackage = UserPackage::orderBy('id', 'DESC')->paginate(25);
		else
			$allUserPackage = UserPackage::orderBy('id', 'DESC')->where('userId','=',encryptor('decrypt', request()->session()->get('user')))->paginate(25);
		
        return view('userpackage.index', compact('allUserPackage'));
    }

    public function addForm(){
		$user = DB::select("select users.name,users.companyId,users.mobileNumber,companies.company_name from users join companies on users.companyId=companies.companyId where users.status=1 and users.roleId=7 order by users.id");
		$allPackage = Package::orderBy('id', 'DESC')->get();
		
		return view('userpackage.add_new', compact(['user','allPackage']));
    }
	
	public function getCoupon(){
		$error=0;
		$dis=0;
		$code=$_GET['couponCode'];
        $coupon=Coupon::where('status',1)->where('code',$code)->first();

		if($coupon){
			if($coupon->numberOfCoupon>0 && ($coupon->numberOfCoupon - $coupon->stock) > 0)
				$dis=$coupon->discount;
			elseif($coupon->startAt && strtotime($coupon->startAt.' 00:00:00') < time() && strtotime($coupon->endAt.' 13:59:00') > time())
				$dis=$coupon->discount;
			else
				$error="Coupon Code is not valid";
		}else{
			$error="Coupon Code is not valid";
		}
		
		echo json_encode(array("dis"=>$dis,"error"=>$error));
    }
	
    public function store(NewUserPackageRequest $request){
		
		$pack=explode("-",$request->packageId);
		$due=$pack[1];
		$packold=DB::select("select DATEDIFF(endAt,now()) as r from user_packages where companyId=$request->companyId and endAt > now() order by id DESC limit 1");
		if($packold && $packold[0]->r > 0)
			$due=$packold[0]->r + $pack[1];

        try {
            $up 			= new UserPackage;
            $up->companyId 	= $request->companyId;
            $up->couponCode = $request->couponCode;
            $up->discount 	= $request->total_dis;
            $up->packageId 	= $pack[0];
            $up->price 		= $request->sub_total;
			
            $up->status 	= 1;
            $up->userId 	= encryptor('decrypt', $request->userId);

            if(!!$up->save()) return redirect(route(currentUser().'.allUserPackage'))->with($this->responseMessage(true, null, 'User Package created'));

        } catch (Exception $e) {
			//dd($e);
            return redirect(route(currentUser().'.allUserPackage'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
	
    public function delete($id){
        try {
			$up = UserPackage::find(encryptor('decrypt', $id));
			if(currentUser()=="superadmin"){
				if(!!$up->delete())
					$msg='User Package deleted';
			}else{
				if($up->startAt!=date('Y-m-d')){
					if(!!$up->delete())
						$msg='User Package deleted';
				}else{
					return redirect()->back()->with($this->responseMessage(false, 'error', 'You cannot delete this package'));
				}
			}
			return redirect()->back()->with($this->responseMessage(true, null, $msg));

        }catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }

}
