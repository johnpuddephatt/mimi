<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
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

    protected $appends = ['playlist','thumbnail'];

    public function video()
    {
      return $this->hasOne('App\Lesson');
    }

    public function comment()
    {
      return $this->hasOne('App\Comment');
    }

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('processed', function (Builder $builder) {
            $builder->whereNotNull('converted_for_streaming_at');
        });
   }

    public function getPlaylistAttribute() {
      if(Storage::disk($this->disk)->exists("/video/processed/{$this->id}.m3u8")) {
         return Storage::disk($this->disk)->url("/video/processed/{$this->id}.m3u8");
      }
   }

   public function getThumbnailAttribute() {
     if($this->thumbnail_path) {
        return Storage::disk($this->disk)->url($this->thumbnail_path);
     }
   }

   public function getTimeToConvertAttribute() {
      return Carbon::parse($this->created_at)->diffInSeconds(Carbon::parse($this->converted_for_streaming_at));
   }
}
