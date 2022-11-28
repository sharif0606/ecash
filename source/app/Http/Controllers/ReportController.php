<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicineStock;
use App\Models\Purchase;
use App\Models\Bill;
use App\Models\Supplier;
use App\Models\Product;
use DB;
use Carbon\Carbon;

class ReportController extends Controller
{
	public function stockBatch(Request $request){
        $allProduct = Product::select("id","name")->orderBy('id', 'DESC')->get();
		
		$where['stocks.companyId']=company()['companyId'];
		if(isset($_GET['product']) && $_GET['product'])
			$where['stocks.productId']=$_GET['product'];
		
		$end = $request->todate?$request->todate:date("Y-m-d");
		$start= $request->fromdate?$request->fromdate:date("Y-m-d",strtotime ( '-365 days' , strtotime ( $end ) ));
		
		$batchWiseReport = DB::table('stocks')
						->join('products', 'stocks.productId',  '=','products.id')
						->select('products.name', 'stocks.batchId','stocks.sellPrice','stocks.buyPrice', 'stocks.stock')
						->where($where)
						->whereBetween('stocks.created_at', [$start, $end])
						->orderBy('products.name')
						->orderBy('stocks.batchId')
						->get();
						
		return view('report.stock_batch_report', compact('batchWiseReport','allProduct'));
    }
	
    public function index(){
		$where['companyId']=company()['companyId'];
		
		if(isset($_GET['dateQuery']) && $_GET['dateQuery']){
			$dateQ 	= explode('-',$_GET['dateQuery']);
			$start 	=date("Y-m-d",strtotime(str_replace('/', '-', $dateQ[0])));
			$end 	=date("Y-m-d",strtotime(str_replace('/', '-', $dateQ[1])));
		}
		else{
			$start = date("Y-m-d");
			$end = date("Y-m-d",strtotime ( '+29 days' , strtotime ( $start ) ));
		}
		
			$allMedicineExpiry = MedicineStock::select('*')->whereBetween('expiryDate', [$start, $end])
			->where($where)
			->orderBy('id', 'DESC')
			->paginate(25);
		
        return view('report.index', compact('allMedicineExpiry'));
	}
	
	public function allPurchaseReport(){
		
		$where['companyId']=company()['companyId'];
		
		if(isset($_GET['dateQuery']) && $_GET['dateQuery']){
			$dateQ 	= explode('-',$_GET['dateQuery']);
			$start 	=date("Y-m-d",strtotime(str_replace('/', '-', $dateQ[0])));
			$end 	=date("Y-m-d",strtotime(str_replace('/', '-', $dateQ[1])));
		}else{
			$start = date("Y-m-d");
			$end = date("Y-m-d",strtotime ( '+29 days' , strtotime ( $start ) ));
		}
		if(isset($_GET['purchase_term']) && $_GET['purchase_term']){
			$where['purchase_term']=$_GET['purchase_term'];
		}
		
		$allPurchaseReport = Purchase::select('*')->whereBetween('purchase_date', [$start, $end])
			->where($where)
			->orderBy('id', 'DESC')
			->paginate(25);
			
		return view('report.allPurchaseReport', compact('allPurchaseReport'));
	}
	
	public function allSaleReport(){
		$where['companyId']=company()['companyId'];
		
		if(isset($_GET['dateQuery']) && $_GET['dateQuery']){
			$dateQ 	= explode('-',$_GET['dateQuery']);
			$start 	=date("Y-m-d",strtotime(str_replace('/', '-', $dateQ[0])));
			$end 	=date("Y-m-d",strtotime(str_replace('/', '-', $dateQ[1])));
		}else{
			$start = date("Y-m-d");
			$end = date("Y-m-d",strtotime ( '+29 days' , strtotime ( $start ) ));
		}
		if(isset($_GET['bill_term']) && $_GET['bill_term']){
			$where['bill_term']=$_GET['bill_term'];
		}
		
		$allSaleReport = Bill::select('*')->whereBetween('bill_date', [$start, $end])
			->where($where)
			->orderBy('id', 'DESC')
			->paginate(25);
			
		return view('report.allSaleReport', compact('allSaleReport'));
	}
	
	public function allSellReportSummary(){
		// return Carbon::now()->subDays(7);
		$todaySellSummary = Bill::select(DB::raw("SUM(bills.total_amount) as todayTotalSellAmount"), DB::raw("SUM(bill_items.basic) as todayTotalProductAmount"))->join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
		->whereDate('bills.bill_date',Carbon::today())->get();

		$yesterdaySellSummary = Bill::select(DB::raw("SUM(bills.total_amount) as yesterdayTotalSellAmount"), DB::raw("SUM(bill_items.basic) as yesterdayTotalProductAmount"))->join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
		->whereDate('bills.bill_date',Carbon::yesterday())->get();

		$weeklySellSummary = Bill::select(DB::raw("SUM(bills.total_amount) as weeklyTotalSellAmount"), DB::raw("SUM(bill_items.basic) as weeklyTotalProductAmount"))->join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
		->whereDate('bills.bill_date','>=',Carbon::now()->subDays(7))->get();

		$monthlySellSummary = Bill::select(DB::raw("SUM(bills.total_amount) as monthlyTotalSellAmount"), DB::raw("SUM(bill_items.basic) as monthlyTotalProductAmount"))->join('bill_items', 'bill_items.bill_id', '=', 'bills.id')
		->whereDate('bills.bill_date','>=',Carbon::now()->subDays(30))->get();
			
		return view('report.allSaleReportSummary', compact('todaySellSummary', 'yesterdaySellSummary', 'weeklySellSummary', 'monthlySellSummary'));
	}
    
	
    
	public function batchWiseProfit(){
        $allProduct = Product::select("id","brandName")->orderBy('id', 'DESC')->get();
		$where="";
		$company_id=company()['companyId'];
		if(isset($_GET['product']) && $_GET['product']){
			$where="and bill_items.item_id=".$_GET['product'];
		}
		
		if(isset($_GET['dateQuery']) && $_GET['dateQuery']){
			$dateQ 	= explode('-',$_GET['dateQuery']);
			$start 	=date("Y-m-d",strtotime(str_replace('/', '-', $dateQ[0])));
			$end 	=date("Y-m-d",strtotime(str_replace('/', '-', $dateQ[1])));
		}else{
			$start = date("Y-m-d");
			$end = date("Y-m-d",strtotime ( '+29 days' , strtotime ( $start ) ));
		}
		
		$profit=DB::select(DB::raw("SELECT sum(`amount`) as sell, ((sum(`qty`)+sum(IFNULL(free,0))) * (select (sum(purchase_items.amount) / sum((IFNULL(purchase_items.qty,0)+IFNULL(purchase_items.free,0))))
		                            from purchase_items where bill_items.batchId= purchase_items.batchId
		                            and bill_items.item_id=purchase_items.item_id)) as cost,
		                            batchId,(select concat(sku,'-',brandName) from products where id=bill_items.item_id) as medicine
		                            FROM `bill_items` where bill_items.companyId=$company_id $where 
		                            and bill_items.bill_id in (select id from bills where bill_date between '$start' and '$end') 
		                            GROUP BY bill_items.batchId,bill_items.item_id")); 
        
		
		
		return view('report.batch_profit_report', compact('profit','allProduct'));
    }
}
