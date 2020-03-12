<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ConvertVideoForDownloading;
use App\Jobs\ConvertVideoForStreaming;
use App\Video;

class VideoController extends Controller
{
    public function index() {
        $videos = Video::latest()->paginate(9);
        return view('welcome', compact('videos'));
    }

    public function done() {
        return 'Done';
    }

    public function store(StoreVideoRequest $request)
    {
        $video = Video::create([
            'disk'              => 'public',
            'video_path'        => $request->video->store('/video/original', 'public'),
            'thumbnail_path'    => $request->thumbnail->store('/video/thumbnail', 'public')
        ]);

        $this->dispatch(new ConvertVideoForStreaming($video));

        return response()->json([
            'id' => $video->id,
        ], 201);
    }
}
