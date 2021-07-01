<?php

namespace App\Http\Controllers;

// use FFMpeg\FFMpeg;
use App\Models\Upload;
use FFMpeg\Media\Video;
use Illuminate\Http\Request;
use App\Jobs\ConvertingVideo;
use FFMpeg\Format\Video\X264;
use FFMpeg\Filters\Video\VideoFilters;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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
        $r = $uploadedFile->storeAs('uploads',$filename);
        
        $upload = new Upload;
        $upload->user_id = auth()->user()->id;
        $upload->filename = $filename;
        $upload->title = $request->title;
        $upload->description = $request->description;

        $upload->save();
       
        $job = new ConvertingVideo($filename,$upload->id);
        dispatch($job);

        return redirect()->back()->with(['success' , 'Your Video Has Been Uploaded Succesfully!']);
        
        
    }

  

    
}
