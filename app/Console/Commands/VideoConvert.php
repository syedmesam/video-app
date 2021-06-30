<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSVideoFilters;

class VideoConvert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert a video into HLS format';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lowFormat  = (new X264('aac'))->setKiloBitrate(500);
        $highFormat = (new X264('aac'))->setKiloBitrate(1000);

        $this->info('Converting redfield.mp4');

        FFMpeg::fromDisk('local')
            ->open('/uploads/'.'1625091787error video.mp4')
            ->exportForHLS()
            ->addFormat($lowFormat, function (HLSVideoFilters $filters) {
                $filters->resize(1280, 720);
            })
            ->addFormat($highFormat)
            ->onProgress(function ($progress) {
                $this->info("Progress: {$progress}%");
            })
            ->toDisk('public')
            ->save('videos/converted.m3u8');

        $this->info('Done!');
    }
}
