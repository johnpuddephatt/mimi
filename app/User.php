<?php

namespace App;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'photo', 'email', 'password', 'description', 'is_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean'
    ];

    protected static function boot() {
      parent::boot();
      static::saving(function ($model) {
        $model->photo = $model->photo->store('users/thumbnail', 'public');
        $model_photo_path = Storage::disk('public')->path($model->photo);
        \Image::make($model_photo_path)->orientate()->fit(480,480)->save();
      });
    }

    public function courses()
    {
      return $this->belongsToMany('App\Course', 'enrolments');
    }


}
