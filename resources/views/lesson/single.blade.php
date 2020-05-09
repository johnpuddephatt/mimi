@extends('layouts.app')

@section('content')
<section class="section is-medium">
  <div class="container">
    <h3 class="title has-text-centered is-size-2">The chat room 💬</h3>
    <p class="subtitle has-text-centered is-size-3">{{str_pad($lesson->number, 2, '0', STR_PAD_LEFT)}}. {{ $lesson->title}}</p>

    <div class="columns is-centered">
      <div class="column is-10-tablet is-9-desktop is-8-widescreen">
        <a class="back-link has-text-dark" href={{ route("course.single", ['course' => $lesson->course->id ])}}>&larr; Back to lessons</a>
        <div class="card instruction-card">
          <div class="card-image">
            <video-player autoplay="true" source="{{ $lesson->video->playlist }}" type="application/x-mpegURL"></video-player>
          </div>
          <div class="card-content">
            <h3 class="is-size-4">Istruzioni 📝</h3>
            <div>{{ $lesson->instructions }}</div>
          </div>
        </div>

        <reply :lesson_id="{{ $lesson->id }}" :user_id="{{ Auth::user()->id }}"></reply>

        @foreach($lesson->comments as $comment)
          @if($comment->video->playlist)
            <div class="card reply-card">
              <div class="column is-paddingless is-6-widescreen">

                <video-player source="{{ $comment->video->playlist }}" type="application/x-mpegURL"></video-player>
                <div class="comment-author">
                  <figure class="image is-32x32">
                    <img class="is-rounded" src="{{url($comment->user->photo) }}" />
                  </figure>
                  <p class="is-size-7">{{$comment->user->first_name}}</p>
                </div>
              </div>
              <div class="column is-6-widescreen">
                <div class="section has-text-centered">
                  <br><br>
                  <p>Comments will be shown here</p>
                  <br>
                  <b-button disabled>Write a reply</b-button>
                </div>
              </div>
            </div>
          @else

          @endif
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection
