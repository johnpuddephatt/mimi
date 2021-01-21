@extends('layouts.app')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Activities 🔭</h3>
            <p class="subtitle has-text-centered">Take a look at what’s been happening</p>
            <div class="panel is-shadowless is-bordered">
              @foreach($activities as $activity)
                 <div class="panel-block is-justify-between">
                  @if(class_basename($activity->subject_type) == 'Comment')
                    <figure class="image is-64x64 mr-2 is-align-self-flex-start is-flex-shrink-0">
                        <img class="is-rounded" src="{{ $activity->causer->photo }}" alt="Image">
                    </figure>
                    <div class="media-content mr-2">
                        <p>
                          <strong>{{ $activity->causer->first_name }}</strong> commented on
                          @if( $activity->subject->user->id == $activity->causer->id )
                            their own reply:
                          @else
                            {{ $activity->subject->user->first_name }}’s reply:<p>
                          @endif
                        </p>
                        <div class="is-italic is-size-7 mb-2">{!!$activity->properties['attributes']['value'] !!}</div>
                        <timeago class="is-size-7 has-text-grey" datetime="{{$activity->created_at}}" :auto-update="60"></timeago>
                    </div>
                    <div>
                  </div>
                  <a class="button" href="{{ route('lesson.reply', [
                    'course' => $activity->subject->reply->lesson->course->id,
                    'lesson' => $activity->subject->reply->lesson->id,
                    'reply_id' => $activity->subject->reply->id ]) }}">View</a>
                  @endif

                  @if(class_basename($activity->subject_type) == 'Reply')
                    @if($activity->subject && $activity->subject->reply)
                      {{-- TEACHER FEEDBACK --}}
                      <figure class="image is-64x64 mr-2 is-align-self-flex-start is-flex-shrink-0">
                          <img class="is-rounded" src="{{ $activity->subject->user->photo }}" alt="Image">
                      </figure>
                      <div class="media-content mr-2">
                          <p><strong>{{ $activity->subject->user->first_name }}</strong> gave feedback to {{ $activity->subject->reply->user->first_name }}</strong></p>
                          <timeago class="is-size-7 has-text-grey" datetime="{{$activity->created_at}}" :auto-update="60"></timeago>
                      </div>
                      <div>
                      <a class="button" href="{{ route('lesson.reply', [
                        'course' => $activity->subject->reply->lesson->course->id,
                        'lesson' => $activity->subject->reply->lesson->id,
                        'reply_id' => $activity->subject->reply->id,
                        'show_feedback' => true ]) }}">View</a>
                    </div>
                  @elseif($activity->subject)
                      {{-- STUDENT REPLY --}}
                      <figure class="image is-64x64 mr-2 is-align-self-flex-start is-flex-shrink-0">
                          <img class="is-rounded" src="{{ $activity->subject->user->photo }}" alt="Image">
                      </figure>
                      <div class="media-content mr-2">
                          <p><strong>{{ $activity->subject->user->first_name }}</strong> posted a reply</p>
                          <timeago class="is-size-7 has-text-grey" datetime="{{$activity->created_at}}" :auto-update="60"></timeago>
                      </div>
                      <a class="button" href="{{ route('lesson.reply', [
                        'course' => $activity->subject->lesson->course->id,
                        'lesson' => $activity->subject->lesson->id,
                        'reply_id' => $activity->subject->id,
                        'show_feedback' => false ]) }}">View</a>
                    @endif

                  @endif

                </div>
              @endforeach
            </div>
            {{ $activities->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
