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
              <video-player ref="instructionVideo" autoplay="true" source="{{ $lesson->video->playlist }}" type="application/x-mpegURL"></video-player>
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

        <create-reply type="video" :lesson_id="{{ $lesson->id }}" :user='@json(Auth::user()->only(['id','first_name','photo']))'></create-reply>
        @foreach($lesson->comments as $comment)
          <div class="column is-full is-half-tablet is-one-third-widescreen is-one-quarter-fullhd is-relative">
            <reply-card
              @if(isset($comment_id) && ($comment->id == $comment_id)) :is_open="true" @endif
              type="video"
              comment_id="{{ $comment->id }}"
              lesson_id="{{ $lesson->id }}"
              :user='@json($comment->user->only(['id','first_name','photo']))'
              thumbnail="{{ $comment->video->thumbnail }}"
              video="{{ $comment->video->playlist }}"
              @if($comment->videoComments->count()) response_playlist="{{$comment->videoComments->first()->video->playlist }}" response_thumbnail="{{$comment->videoComments->first()->video->thumbnail }}" @endif
              time="{{ $comment->video->converted_for_streaming_at }}"
              @if(Auth::user()->is_admin) :admin='@json(Auth::user()->only(['id','first_name','photo']))' @endif
              >
            </reply-card>
            {{-- @if(Auth::user()->is_admin)
              @if($comment->videoComments->count())
                <b-tooltip  label="You’ve replied to this" type="is-dark" animated position="is-left" :delay="1000" class="admin-check-button--tooltip">
                  <b-icon class="admin-check-button" type="is-light" icon="check"/>
                </b-tooltip>
              @else
                <create-reply type="video" :comment_id="{{ $comment->id }}" :lesson_id="{{ $lesson->id }}" :user='@json(Auth::user()->only(['id','first_name','photo']))'></create-reply>
              @endif
            @endif --}}
          </div>
        @endforeach
      </div>
    @else
      <div class="notification is-warning">Lesson video has not yet processed. Please try again later.</div>
    @endif
  </div>
</section>
@endsection
