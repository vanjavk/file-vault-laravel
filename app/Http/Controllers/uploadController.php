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
       $path = $request->file('uploadfile')->store('files');
   	function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
	    $pieces = [];
	    $max = mb_strlen($keyspace, '8bit') - 1;
	    for ($i = 0; $i < $length; ++$i) {
	        $pieces []= $keyspace[random_int(0, $max)];
	    }
	    return implode('', $pieces);
	}
       try {

           $api_key = getenv('VT_API_KEY') ? getenv('VT_API_KEY') : 'ade0faaaddfc1639b015665b97fd5d683f794c6a2f05ab906baa6ea7f929f355';

           $cfile = curl_file_create(substr(realpath(dirname(__DIR__)), 0, -8).'storage/app/'.$path);
           #print_r($path);

           $post = array('apikey' => $api_key, 'file' => $cfile);
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, 'https://www.virustotal.com/vtapi/v2/file/scan');
           curl_setopt($ch, CURLOPT_POST, True);
           curl_setopt($ch, CURLOPT_VERBOSE, 1); // remove this if your not debugging
           curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); // please compress data
           curl_setopt($ch, CURLOPT_USERAGENT, "gzip, My php curl client");
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
           $result = curl_exec($ch);
           $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
           #print("status = $status_code\n");
           if ($status_code == 200) { // OK
               $js = json_decode($result, true);
               $viruspl=$js['permalink'];
               #print_r($js);
           }

       } catch (Exception $e) {
   	       $viruspl='';
       }
       $sha256=hash_file('sha256',substr(realpath(dirname(__DIR__)), 0, -8).'storage/app/'.$path);


    File::create(['id'=>random_str(16),'sha256'=>$sha256, 'viruspl'=>$viruspl, 'name'=>$request->file('uploadfile')->getClientOriginalName(), 'size'=>$request->file('uploadfile')->getClientSize(), 'location'=>$path, 'uploader'=>Auth::id(), 'public'=>0]);
    
 	return back();
    
   }
}
