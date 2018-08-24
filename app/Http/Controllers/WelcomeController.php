<?php

namespace App\Http\Controllers;
use App\File;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
class WelcomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        function formatUnitSize($bytes)
        {
            if ($bytes >= 1073741824)
            {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            }
            elseif ($bytes >= 1048576)
            {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            }
            elseif ($bytes >= 1024)
            {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            }
            elseif ($bytes > 1)
            {
                $bytes = $bytes . ' bytes';
            }
            elseif ($bytes == 1)
            {
                $bytes = $bytes . ' byte';
            }
            else
            {
                $bytes = '0 bytes';
            }
            return $bytes;
        }
        Auth::routes();
        if(Auth::guest())
        {
            return view('welcome');
        }
        else
        {
            
            $files = File::where('uploader','=', Auth::id())->orderBy('created_at')->get();
            #die(Auth::id());
            foreach($files as $k=>$v)
            {
                $files[$k]['size']=formatUnitSize($files[$k]['size']);
            }
            return view('home', ['files' => $files]);
        }
    }
}
