@extends('layouts.app')

@section('content')
<section class="section is-medium">
  <div class="container">
    <h3 class="title has-text-centered is-size-2">The chat room 💬</h3>
    <p class="subtitle has-text-centered is-size-3">{{str_pad($lesson->number, 2, '0', STR_PAD_LEFT)}}. {{ $lesson->title}}</p>
    @if($lesson->video)
      <div class="columns is-centered">
        <div class="column is-12-tablet is-9-desktop is-8-widescreen is-paddingless">
          <a class="back-link has-text-dark" href={{ route("course.single", ['course' => $lesson->course->id ])}}>&larr; Back to lessons</a>
          <div class="card instruction-card">
            <div class="card-image">
              <video-player ref="instructionVideo" should_autoplay="true" source="{{ $lesson->video->playlist }}" type="application/x-mpegURL"></video-player>
            </div>
            <div class="card-content">
              <h3 class="is-size-4">Istruzioni 📝</h3>
              <div>{{ $lesson->instructions }}</div>
            </div>
          </div>
        </div>
      </div>

      <h2 class="subtitle is-size-4 has-text-centered">Responses</h2>
      <div class="columns is-multiline">
        <create-reply :lesson_id="{{ $lesson->id }}" :user='@json(Auth::user()->only(['id','first_name','photo']))'></create-reply>
        @foreach($lesson->replies as $reply)
          <reply-card
            @if(isset($reply_id) && ($reply->id == $reply_id))
              :auto_open="true"
              @if(isset($show_feedback) && $show_feedback) :show_feedback="true" @endif
            @endif
            comments_count="{{ $reply->comments_count }}"
            reply_id="{{ $reply->id }}"
            lesson_id="{{ $lesson->id }}"
            @if($reply->user) :user='@json($reply->user->only(['id','first_name','photo']))' @endif
            :active_user='@json(Auth::user()->only(['id','first_name','photo']))'
            thumbnail="{{ $reply->video->thumbnail }}"
            video="{{ $reply->video->playlist }}"
            @if($reply->feedback)
              feedback_id="{{ $reply->feedback->id }}"
              feedback_playlist="{{$reply->feedback->video->playlist }}"
              feedback_thumbnail="{{$reply->feedback->video->thumbnail }}"
            @endif
            time="{{ $reply->video->converted_for_streaming_at }}"
            @if(Auth::user()->is_admin) :admin_user='@json(Auth::user()->only(['id','first_name','photo']))' @endif
            class="column is-full is-half-tablet is-one-third-widescreen is-one-quarter-fullhd is-relative"
            >
          </reply-card>
        @endforeach
      </div>
    @else
      <div class="notification is-warning">Lesson video has not yet processed. Please try again later.</div>
    @endif
  </div>
</section>
@endsection
