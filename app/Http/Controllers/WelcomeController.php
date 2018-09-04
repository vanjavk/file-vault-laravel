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
            
            #var_dump($files);
            #die("xdebug");
            #print_r($files);
            foreach($files as $k=>$v)
            {
                if ($files[$k]['scanned']==0){
                    $post = array('apikey' => 'ade0faaaddfc1639b015665b97fd5d683f794c6a2f05ab906baa6ea7f929f355','resource'=>$files[$k]['sha256']);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://www.virustotal.com/vtapi/v2/file/report');
                    curl_setopt($ch, CURLOPT_POST,1);
                    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); // please compress data
                    curl_setopt($ch, CURLOPT_USERAGENT, "gzip, My php curl client");
                    curl_setopt($ch, CURLOPT_VERBOSE, 1); // remove this if your not debugging
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

                    $result = curl_exec ($ch);
                    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    #print("status = $status_code\n");
                    if ($status_code == 200) { // OK
                        $js = json_decode($result, true);
                        #print_r($js);
                    }
                    curl_close ($ch);
                    if (isset($js['positives']))
                    {
                        $virusresult=(string)$js['positives'].'/'.(string)$js['total'];
                        File::where('id', $files[$k]['id'])->update(['scanned' => 1]);
                        File::where('id', $files[$k]['id'])->update(['virusresult' => $virusresult]);
                        File::where('id', $files[$k]['id'])->update(['viruspl' => $js['permalink']]);
                    }
                }
            }
            $files = File::where('uploader','=', Auth::id())->orderBy('created_at')->get();

            foreach($files as $k=>$v) {

                $files[$k]['size']=formatUnitSize($files[$k]['size']);
            }

            return view('home', ['files' => $files]);
        }
    }
}
