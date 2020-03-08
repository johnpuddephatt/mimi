<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
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
            'disk'          => 'public',
            'original_name' => $request->video->getClientOriginalName(),
            'path'          => $request->video->store('/video/original', 'public'),
            'title'         => $request->title,
        ]);

        $this->dispatch(new ConvertVideoForStreaming($video));

        return response()->json([
            'id' => $video->id,
        ], 201);
    }
}
