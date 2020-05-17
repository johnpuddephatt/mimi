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

    public $timeout = 1800;

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
        $lowBitrateFormat  = (new X264('aac','libx264'))->setKiloBitrate(200)->setAdditionalParameters(
          [
            "-r",
            16.667
          ]
        );
        $highBitrateFormat  = (new X264('aac','libx264'))->setKiloBitrate(1000)->setAdditionalParameters(
          [
            "-r",
            16.667
          ]
        );

        // open the uploaded video from the right disk...
        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->video_path)

            // Add filter
            ->addFilter('-vf', "crop='min(iw,ih)':'min(iw,ih)',scale=480:480")
           // call the 'exportForHLS' method and specify the disk to which we want to export...
            ->exportForHLS()
            ->toDisk($this->video->disk)
           // we'll add different formats so the stream will play smoothly
            ->addFormat($lowBitrateFormat)
            ->addFormat($highBitrateFormat)
           // call the 'save' method with a filename...
            ->save('video/processed/' . $this->video->id . '.m3u8')

            ->getFrameFromSeconds(0)
            ->export()
            ->toDisk($this->video->disk)
            ->save('video/thumbnail/'. $this->video->id . '.jpg');

           // update the database so we know the convertion is done!
           $this->video->update([
               'converted_for_streaming_at' => Carbon::now(),
           ]);
    }
}
