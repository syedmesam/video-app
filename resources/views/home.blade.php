@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    
                <a href="{{route('all.videos')}}">Click To See Uploaded Videos</a>
                    
                </div>
                @if (session()->has('message'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div class="card text-center">
                    <form action="{{route('upload')}}" method="POST" enctype="multipart/form-data">
                        <div>@csrf</div>   
                        <h3>Upload Video</h3>
                        <label for="title"><h5>Title</h5></label>
                        <input type="text" name="title" >
                        {{-- <br> --}}
                        <label for="title"><h5>Description</h5></label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" 
                        rows="3" name="description" maxlength="200"></textarea>
                        {{-- <label for="title"><h5>Keywords</h5></label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="keywords"></textarea> --}}
                        {{-- <label class="form-label" for="customFile">Default file input example</label> --}}
                        <input type="file" class="form-control" id="customFile" name="video" />
                        <button type="submit" class="btn btn-primary btn-lg" style="margin: 5px;">Upload</button>
                    </form>
                </div>
                
 
                    @if ($errors->has('title'))
                    <div class="alert alert-danger text-center">
                        <strong>{{ $errors->first('title') }}</strong> 
                      </div>
                       
                    @endif
                    @if ($errors->has('description'))
                    <div class="alert alert-danger text-center">
                        <strong>{{ $errors->first('description') }}</strong> 
                      </div>
                       
                    @endif
                    @if ($errors->has('video'))
                    <div class="alert alert-danger text-center">
                        <strong>{{ $errors->first('video') }}</strong> 
                      </div>
                       
                    @endif
                
            </div>
        </div>
    </div>
    
</div>
@endsection