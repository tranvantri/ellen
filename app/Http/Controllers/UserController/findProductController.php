<?php
namespace App\Http\Controllers\UserController;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Product;

use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AskExport;
use App\Imports\AskImport;


class findProductController extends Controller
{

	public function timSanPham(Request $request){

		$name = $request->timsanpham;
		
		$products = Product::where('enable','=', 1)->where('name','LIKE',"%".$name.'%')->paginate(9);

        $dirName = 'Tất cả sản phẩm';
        
        return view('user/tatcasanpham',compact('products', 'dirName'));
	}

	public function export() 
    {
        return Excel::download(new AskExport, 'Asks.xlsx');
    }
	
    public function import() 
    {
	Excel::import(new AskImport, 'asks.xlsx');
    }


}