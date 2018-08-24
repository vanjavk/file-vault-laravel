<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class File extends Model
{
	protected $fillable = [
        'id', 'name', 'uploader', 'location', 'size', 'public'
    ];
    public $incrementing = false;
    public function storefile(Request $request)
    {
        // Validate the request...
    	

        $flight = new Flight;

        $flight->id = $request->id;

        $flight->name = $request->name;

        $flight->uploader = $request->uploader;

        $flight->location = $request->location;

        $flight->size = $request->size;

        $flight->public = $request->public;

        $flight->save();

    }
}
