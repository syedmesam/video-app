<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use FFMpeg\Media\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    /**
     * Upload file .
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function uploadVideo(Request $request)
    {
        
        $request->validate([
            'title' => ['required','string','max:20'],
            'description' => ['required','string','max:200'],
            'video' => ['required','mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi'],
        ]);
        $uploadedFile = $request->file('video');
        $filename = time().$uploadedFile->getClientOriginalName();

        Storage::disk('local')->putFileAs(
            'files/'.$filename,
            $uploadedFile,
            $filename
          );

        $upload = new Upload;
        $upload->user_id = auth()->user()->id;
        $upload->filename = $filename;
        $upload->title = $request->title;
        $upload->description = $request->description;

        $upload->save();

        
        dd($filename);
        // return view('home');
    }

    function getVideo() 
    {
        $u = Upload::find(1);
        $name = $u->filename;
        $video = Storage::disk('local')->get("files/{$name}/{$name}");
        $response = Response::make($video, 200);
        $response->header('Content-Type', 'video/mp4');
        return $response;
    }

    
}
