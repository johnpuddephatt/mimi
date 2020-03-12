<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Video extends Model
{

    protected $fillable = [
        'disk',
        'video_path',
        'thumbnail_path',
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

   public function getTimeToConvertAttribute() {
      return Carbon::parse($this->created_at)->diffInSeconds(Carbon::parse($this->converted_for_streaming_at));
   }
}
