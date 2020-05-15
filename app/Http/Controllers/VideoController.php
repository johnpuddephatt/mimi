<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideo;
use Illuminate\Support\Facades\Storage;
use App\Jobs\RotateAndCropImage;
use App\Jobs\ConvertVideoForStreaming;
use App\Video;
use App\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoController extends Controller
{
    public function single(Lesson $lesson, Video $video) {
      return response()->json($video);
    }

}
