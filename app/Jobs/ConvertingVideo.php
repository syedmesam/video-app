<?php

namespace App\Jobs;

use App\Models\Upload;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSVideoFilters;

class ConvertingVideo 
// implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $filename;
    private $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename,$id)
    {
        $this->filename = $filename;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lowFormat  = (new X264('aac'))->setKiloBitrate(500);
        $highFormat = (new X264('aac'))->setKiloBitrate(1000);
        $originalName = pathinfo($this->filename, PATHINFO_FILENAME);
        FFMpeg::fromDisk('local')
            ->open('uploads/' . $this->filename)
            ->exportForHLS()
            ->addFormat($lowFormat, function (HLSVideoFilters $filters) {
                $filters->resize(1280, 720);
            })
            ->addFormat($highFormat)
            ->toDisk('public')
            ->save("videos/converted{$originalName}.m3u8");

        //Updating DB converted status after converted

        $upload = Upload::findOrFail($this->id);
        $upload->converted_name = "converted{$originalName}.m3u8";
        $upload->converted_status = true;
        $upload->save();

        return 0;
    }
}
