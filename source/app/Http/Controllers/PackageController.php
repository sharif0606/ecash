<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Branch;
use App\Models\UserPackage;
use App\Models\Notification;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Requests\Package\NewPackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Http\Traits\ResponseTrait;
use Exception;
use Carbon\Carbon;
use DB;


class PackageController extends Controller
{
	use ResponseTrait;
    public function index(){
        $allPackage = Package::orderBy('id', 'DESC')->paginate(25);
        return view('package.index', compact('allPackage'));
    }

    public function addForm(){
        return view('package.add_new');
    }

    public function store(NewPackageRequest $request){
        try {
            $package = new Package;
            $package->name = $request->packageName;
            $package->code = $request->packageCode;
            $package->price = $request->price;
            $package->duration = $request->duration;
            $package->canbuy = $request->canbuy;
			
            $package->status = $request->status;
            $package->userId = encryptor('decrypt', $request->userId);

            if(!!$package->save()) return redirect(route(currentUser().'.allPackage'))->with($this->responseMessage(true, null, 'Package created'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.allPackage'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }

    public function editForm($id){
        $package = Package::find(encryptor('decrypt', $id));
        return view('package.edit', compact(['package']));
    }

    public function update(UpdatePackageRequest $request){
        try {
            $package = Package::find(encryptor('decrypt', $request->id));
            $package->name = $request->packageName;
            $package->code = $request->packageCode;
            $package->price = $request->price;
            $package->duration = $request->duration;
            $package->canbuy = $request->canbuy;
			
            $package->status = $request->status;
            $package->userId = encryptor('decrypt', $request->userId);
			
            if(!!$package->save()) return redirect(route(currentUser().'.allPackage'))->with($this->responseMessage(true, null, 'Package updated'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.allPackage'))->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
    
    public function activePackage(){
		
        $allPackages = Package::join('user_packages', 'packages.id', '=', 'user_packages.packageId')->join('users', 'user_packages.requestedBy', '=', 'users.id')
        ->select("user_packages.*","packages.name", "packages.price", "packages.noofbill","users.name as userName","users.mobileNumber")
        ->where('user_packages.status',2)
        ->orderBy("user_packages.id","DESC")->paginate(10);
        return view('package.active_package', compact('allPackages'));
    }
	
	public function reqPackage(){
		if(currentUser() === 'superadmin'){
			$allPackages = UserPackage::whereIn('status',[0,1])->orderBy("id","DESC")->paginate(10);
		}else{
			$allPackages = UserPackage::whereIn('status',[0,1])->orderBy("id","DESC")->paginate(10);
		}
		//dd(DB::getQueryLog()); 
        return view('package.req_package', compact('allPackages'));
    }
	
	public function reqPackagepending(){
		if(currentUser() === 'superadmin'){
			$allPackages = UserPackage::where('status',0)->orderBy("id","DESC")->paginate(10);
		}else{
			$allPackages = UserPackage::where('status',0)->orderBy("id","DESC")->paginate(10);
		}
        return view('package.req_package_pending', compact('allPackages'));
    }
	
	public function packageStatus($status,$id){
        try {
			$upack = UserPackage::find(encryptor('decrypt', $id));
            $upack->status = $status;
            $upack->userId = encryptor('decrypt', request()->session()->get('user'));
            $upack->updated_at = Carbon::now();
            if(!!$upack->save()){
                if($upack->couponCode){
                    if($status==1){
                        $cop = Coupon::where('code',$upack->couponCode)->first();
                        $cop->numberOfCoupon=($cop->numberOfCoupon - 1);
                        $cop->save();
                    }else{
                        $cop = Coupon::where('code',$upack->couponCode)->first();
                        $cop->numberOfCoupon=($cop->numberOfCoupon + 1);
                        $cop->save();
                    }
                }
                return redirect()->back()->with($this->responseMessage(true, null, 'Package Request Accpted'));
            } 
        } catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }

    }
	
	/* for owner */
	public function requestPackage(){
        $allPackages = Package::where('status',1)->orderBy('id', 'DESC')->paginate(10);
        $allBranch = Branch::where(company())->orderBy('id', 'DESC')->get();
        return view('package.request', compact('allPackages','allBranch'));
    }
	/* for owner */
	public function coupon_check(Request $r){
	    //DB::enableQueryLog();
		$cop = Coupon::where('code',$r->coupon)->where('numberOfCoupon','>',0)->where(function($q) {
                        $q->where('startAt', '<=', date("Y-m-d"))
                        ->orWhereNull('startAt');
                    })->where(function($q) {
                        $q->where('endAt', '>=', date("Y-m-d"))
                        ->orWhereNull('endAt');
                    })->first();
            //dd(DB::getQueryLog()); 
            if($cop){
                if($cop->discount > 0 )
                    echo "congratulations! You have got ".$cop->discount."% discount.";
                else
                    echo "congratulations! You have got extra ".$cop->noofbill." invoice.";
            }else{
                return false;
            }
    }
	/* for owner */
	public function spr(Request $r,$id){
        try {
            $id=encryptor('decrypt', $id);
            $pack=Package::find($id);
            if($pack){
                $upackck=UserPackage::where('packageId',$id)->where(company())->where('branchId',$r->branchId)->count();
                if($pack->canbuy <= $upackck){
                    return redirect(route(currentUser().'.requestPackage'))->with($this->responseMessage(false, 'error', 'You cannot buy this package more than '.$pack->canbuy.' times.'));
                }
            }else{
                return redirect(route(currentUser().'.requestPackage'))->with($this->responseMessage(false, 'error', 'This package is not valid.'));
            }
            
            
			$upack = new UserPackage;
            $upack->packageId   = $id;
            $upack->price       = $pack->price;
            $upack->noofbill    = $pack->duration;
            $upack->requestedBy = encryptor('decrypt', request()->session()->get('user'));
            
            if($r->coupon){
                $cop = Coupon::where('code',$r->coupon)->where('numberOfCoupon','>',0)->where(function($q) {
                        $q->where('startAt', '<=', date("Y-m-d"))
                        ->orWhereNull('startAt');
                    })->where(function($q) {
                        $q->where('endAt', '>=', date("Y-m-d"))
                        ->orWhereNull('endAt');
                    })->first();
                if($cop){
                    if($cop->discount > 0 ){
                        $upack->discount= ($pack->price * ($cop->discount/100));
                        $upack->price   = ($pack->price - ($pack->price * ($cop->discount/100)));
                    }else{
                        $upack->discount_coupon =$cop->noofbill;
                        $upack->noofbill        = ($pack->duration + $cop->noofbill);
                    }
                    $upack->couponCode = $r->coupon;
                }
            }
            
            $upack->created_at  = Carbon::now();
			$upack->companyId   = company()['companyId'];
			$upack->branchId 	= $r->branchId;
            $upack->updated_at  = null;
            $upack->status      = 0;

            if(!!$upack->save()) return redirect(route(currentUser().'.requestPackage'))->with($this->responseMessage(true, null, 'Package Request Sent'));

        } catch (Exception $e) {
            return redirect(route(currentUser().'.requestPackage'))->with($this->responseMessage(false, 'error', 'Please try again!'));
        }

    }
	/* for moderator */
	public function myPackage(){
        $allPackages = UserPackage::where(company())->orderBy("id","DESC")->paginate(10);
        return view('package.my_package', compact('allPackages'));
    }
	/* for owner and superadmin */
	public function cancelPackage($id){
        try {
			$note=new Notification;
			
            $upack = UserPackage::find(encryptor('decrypt', $id));
			
			$note->userId=$upack->requestedBy;
            if(!!$upack->delete()){
				$note->msg = "Your Package Request has been cancelled by authority at ".date('d M,Y h:iA');
				$note->status = 0;
				$note->created_at = Carbon::now();
				$note->updated_at = null;
				if(currentUser() == 'superadmin'){
				   $note->save();
				}
                return redirect()->back()->with($this->responseMessage(true, null, 'Package Request deleted'));
            }
        }catch (Exception $e) {
            return redirect()->back()->with($this->responseMessage(false, 'error', 'Please try again!'));
            return false;
        }
    }
}
