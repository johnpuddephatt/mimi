@extends('layouts.app')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Activities 🔭</h3>
            <p class="subtitle has-text-centered">Take a look at what’s been happening</p>
            @foreach($activities as $activity)


              @if(class_basename($activity->subject_type) == 'Comment')
                <a class="mb-4 p-2 has-text-black is-align-items-center is-flex is-justify-between is-outlined is-fullwidth"
                href="{{ route('lesson.reply', ['course' => $activity->properties['attributes']['reply.lesson.course.id'], 'lesson' => $activity->properties['attributes']['reply.lesson.id'], 'reply_id' => $activity->properties['attributes']['reply.id'], 'show_feedback' => false]) }}">
                  <figure class="image is-64x64 mr-2">
                      <img class="is-rounded" src="{{$activity->properties['attributes']['user.photo']}}" alt="Image">
                  </figure>
                  <div class="media-content">
                      <p>
                        {{$activity->properties['attributes']['user.first_name']}} commented on
                        @if($activity->properties['attributes']['reply.user.id'] == $activity->causer_id)
                          their own reply:
                        @else
                          {{$activity->properties['attributes']['reply.user.first_name']}}’s reply:<p>
                        @endif
                      </p>
                      <div class="is-italic">{!!$activity->properties['attributes']['value'] !!}</div>
                      <timeago class="is-size-7 has-text-grey" datetime="{{$activity->created_at}}" :auto-update="60"></timeago>
                  </div>
                </a>
              @endif

              @if(class_basename($activity->subject_type) == 'Reply')
                <a
                  class="mb-4 p-2 has-text-black is-align-items-center is-flex is-justify-between is-outlined is-fullwidth"
                  @if(isset($activity->properties['attributes']['reply']['user']))
                    href="{{ route('lesson.reply', ['course' => $activity->properties['attributes']['reply.lesson']['course_id'], 'lesson' => $activity->properties['attributes']['reply.lesson']['id'], 'reply_id' => $activity->properties['attributes']['reply']['id'], 'show_feedback' => isset($activity->properties['attributes']['reply']['user'])]) }}"
                  @else
                    href="{{ route('lesson.reply', ['course' => $activity->properties['attributes']['lesson.course.id'], 'lesson' => $activity->properties['attributes']['lesson.id'], 'reply_id' => $activity->subject_id, 'show_feedback' => isset($activity->properties['attributes']['reply']['user'])]) }}"
                  @endif
                  >
                  <figure class="image is-64x64 mr-2">
                      <img class="is-rounded" src="{{$activity->properties['attributes']['user.photo']}}" alt="Image">
                  </figure>
                  <div class="media-content">
                      <p>
                        @if(isset($activity->properties['attributes']['reply']['user']))
                          {{$activity->properties['attributes']['user.first_name']}} gave feedback to {{ $activity->properties['attributes']['reply']['user']['first_name'] }} in {{ $activity->properties['attributes']['reply']['lesson']['title'] }}
                        @else
                          {{$activity->properties['attributes']['user.first_name']}} posted a reply in {{ $activity->properties['attributes']['lesson.title'] }}
                        @endif
                      </p>
                      <timeago class="is-size-7 has-text-grey" datetime="{{$activity->created_at}}" :auto-update="60"></timeago>
                  </div>
                </a>
              @endif
            @endforeach
            {{ $activities->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
