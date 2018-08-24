<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\File;
use DB;
use Auth;
class uploadController extends Controller
{
	
    public function index()
    {
    	return view('home');
    }

   public function showUploadFile(Request $request)
   {
   	function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
	    $pieces = [];
	    $max = mb_strlen($keyspace, '8bit') - 1;
	    for ($i = 0; $i < $length; ++$i) {
	        $pieces []= $keyspace[random_int(0, $max)];
	    }
	    return implode('', $pieces);
	}
    $path = $request->file('uploadfile')->store('files');
    File::create(['id'=>random_str(16), 'name'=>$request->file('uploadfile')->getClientOriginalName(), 'size'=>$request->file('uploadfile')->getClientSize(), 'location'=>$path, 'uploader'=>Auth::id(), 'public'=>0]);
    
 	return back();
    
   }
}
