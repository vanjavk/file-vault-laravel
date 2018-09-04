<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class File extends Model
{
    protected $fillable = [
        'id', 'name', 'uploader', 'location', 'size', 'public', 'scanned', 'sha256', 'virusresult', 'viruspl'
    ];
    public $incrementing = false;
}
