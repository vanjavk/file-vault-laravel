@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h1>Upload file</h1><br>
                    <?php
                        echo Form::open(array('url' => '/uploadfile', 'files'=>'true', 'class'=>'form-group'));
                        #echo 'Select the file to upload. ';
                        #echo Form::file(array('id'=>'uploadfile','class'=>'btn btn-primary'));
                        #echo Form::submit('Upload File');
                    ?>
                    <div style="text-align: center;">
                        <input type="file" id="uploadfile" name="uploadfile" style="display:none;">
                        <label for="uploadfile" class="btn btn-primary">Browse...</label>
                        <input type="submit" id="submitfile" class="btn btn-success" value="Upload file"  style="display:none;" name="submit">
                        <label for="submitfile" class="btn btn-success">Upload file</label>
                    </div>
                    <?php
                        echo Form::close();
                    ?><br>
                    @if(!empty($files))
                    @if(count($files)>0)
                    <table class='table table-bordered'><thead class='thead-light'><tr><th>File name</th><th>Size</th><th>Downloads</th><th>Safety</th><th>Options</th></tr>

                    @foreach($files as $v)
                    <tr>
                        <td style=''><a href='download/{{$v->id}}'> {{$v->name}} </a></td>
                        <td style=''>{{$v->size}} </td>
                        <td style=''>{{$v->downloads}}</td>
                        <td style=''><a href='{{$v->viruspl}}'>{{$v->virusresult}}</a></td>
                        @if($v->public==1)
                        <td style=''>
                            <form action='editfileaccess' method='POST' style='float: left; padding-bottom:3px;'> @csrf 
                            <button type='submit' class='btn btn-primary' name = 'fileaccess' value = '{{$v->id}}'>public</button> 
                            </form>
                            <form action='removefile' method='POST'> @csrf 
                            <button type='submit' class='btn btn-danger'  name = 'fileremove' value = '{{$v->id}}'>remove</button>
                            </form>
                        </td>
                        @else
                        <td style=''>
                            <form action='editfileaccess' method='POST' style='float: left; padding-bottom:3px;'> @csrf 
                            <button type='submit' class='btn btn-warning' name = 'fileaccess' value = '{{$v->id}}'>private</button> 
                            </form>
                            <form action='removefile' method='POST'> @csrf 
                            <button type='submit' class='btn btn-danger'  name = 'fileremove' value = '{{$v->id}}'>remove</button>
                            </form>
                        </td>

                        @endif

                    </tr>
                    @endforeach
                    @else
                    <p> </p>
                    @endif
                    @else
                    <p> No file have been uploaded.</p>
                    @endif
                    </thead></table>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
