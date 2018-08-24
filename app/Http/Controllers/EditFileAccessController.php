<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use App\File;
use DB;
use Auth;
class EditFileAccessController extends Controller
{
    public function index()
    {
    	return back();
    }

    public function editfile(Request $request)
    {

      $fileuuid=$request->input('fileaccess');
      $data= File::where('id', $fileuuid)->first();
      if ($data['uploader']==Auth::id())
      {
          if ($data['public']==0)
          {
              File::where('id', $fileuuid)->update(['public' => 1]);
          }
          else
          {
              File::where('id', $fileuuid)->update(['public' => 0]);
          }
      }
    return back();
    }
    public function removefile(Request $request)
    {
      $fileuuid=$request->input('fileremove');
      $data=File::where('id', $fileuuid)->first();
      if ($data['uploader']==Auth::id())
      {
          Storage::delete($data['location']);
          File::where('id', $fileuuid)->delete();
      }
    return back();
    }
    public function download($download)
    {
      
      $data=File::where('id', $download)->first();
      if (!($data['public']==0 && $data['uploader']!=Auth::id()))
      {
          File::where('id', $download)->increment('downloads');
          return Storage::download($data['location']);
      }
    return back();
    }
      
      

      
   
}
