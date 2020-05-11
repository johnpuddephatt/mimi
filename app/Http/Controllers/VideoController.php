<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideo;
use Illuminate\Support\Facades\Storage;
use App\Jobs\RotateAndCropImage;
use App\Jobs\ConvertVideoForStreaming;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoController extends Controller
{
    public function single(Video $video) {
      return response()->json($video);
    }
    //
    // public function index(Request $request, $channel = null) {
    //
    //   if($request->ajax()){
    //     $videos = Video::latest()->get();
    //     return response()->json($videos);
    //   }
    //   else {
    //     return view('welcome');
    //   }
    // }

   //  public function count() {
   //    return response()->json([
   //       'video_count' => Cache::get('video_count', 0)
   //    ], 200);
   // }

    // public function store(StoreVideo $request)
    // {
    //     $video = Video::create([
    //         'disk'              => 'public',
    //         'video_path'        => $request->video->store('video/original', 'public'),
    //         'thumbnail_path'    => $request->thumbnail ? $request->thumbnail->store('video/thumbnail', 'public') : '',
    //     ]);
    //
    //     $this->dispatch(new ConvertVideoForStreaming($video));
    //
    //     if($request->thumbnail) {
    //       $this->dispatch(new RotateAndCropImage($video));
    //     }
    //
    //     return response()->json($video,201);
    // }
}
