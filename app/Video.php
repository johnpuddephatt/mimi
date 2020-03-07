<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{

    protected $fillable = [
        'title',
        'original_name',
        'disk',
        'path',
        'converted_for_streaming_at'
    ];

    protected $dates = [
       'converted_for_streaming_at',
    ];

    public function getPlaylistAttribute() {
      if(Storage::disk($this->disk)->exists("/video/processed/{$this->id}.m3u8")) {
         return Storage::disk($this->disk)->url("/video/processed/{$this->id}.m3u8");
      }
      else {
         return false;
      }
   }
}
