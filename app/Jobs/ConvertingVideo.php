<?php

namespace App\Jobs;

use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSVideoFilters;

class ConvertingVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $fileName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
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

        FFMpeg::fromDisk('local')
            ->open('/uploads/' . $this->fileName)
            ->exportForHLS()
            ->addFormat($lowFormat, function (HLSVideoFilters $filters) {
                $filters->resize(1280, 720);
            })
            ->addFormat($highFormat)
            ->toDisk('public')
            ->save("videos/converted{$this->fileName}.m3u8");
            
            return 0;
    }
}
