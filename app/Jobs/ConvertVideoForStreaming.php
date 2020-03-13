<?php

namespace App\Jobs;

use App\Video;
use Carbon\Carbon;
use FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;


class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    public $timeout = 360;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create some video formats...
        $lowBitrateFormat  = (new X264('aac','libx264'))->setKiloBitrate(200);
        $midBitrateFormat  = (new X264('aac','libx264'))->setKiloBitrate(400);
        $highBitrateFormat = (new X264('aac','libx264'))->setKiloBitrate(600);


        // open the uploaded video from the right disk...
        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->video_path)

            // Add filter
            ->addFilter('-vf', "crop='min(iw,ih)':'min(iw,ih)',scale=480:480")

           // call the 'exportForHLS' method and specify the disk to which we want to export...
            ->exportForHLS()
            ->toDisk($this->video->disk)

           // we'll add different formats so the stream will play smoothly
           // with all kinds of internet connections...
            ->addFormat($lowBitrateFormat)
            ->addFormat($midBitrateFormat)
            ->addFormat($highBitrateFormat)

           // call the 'save' method with a filename...
            ->save('video/processed/' . $this->video->id . '.m3u8');

           // update the database so we know the convertion is done!
           $this->video->update([
               'converted_for_streaming_at' => Carbon::now(),
           ]);

           // Update the cache of
           Cache::forever('video_count', Video::count());

    }
}
