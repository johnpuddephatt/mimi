@extends('layouts.app')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          @if(Auth::user()->is_admin || Auth::user()->courses->count() > 1 )
            <a class="back-link has-text-dark" href={{ route("course.index")}}>&larr; Back to all courses</a>
          @endif
          <div class="box">
            <h3 class="title has-text-centered">{{ $course->title}}</h3>
            <p class="subtitle has-text-centered">{{ $course->description}}</p>

            @forelse ($course->lessons as $lesson)
              <b-button
                tag="a"
                size="is-medium"
                href="{{ route('lesson.single', ['course' => $course->id, 'lesson' => $lesson->id ]) }}"
                class="is-justify-between"
                icon-right="arrow-right"
                expanded
                outlined>
                  {{str_pad($lesson->number, 2, '0', STR_PAD_LEFT)}}.
                  {{ $lesson->title }}

                  @if(Auth::user()->is_admin && $lesson->feedbackless_reply_count())<span class="tag is-primary ml-4" title="{{ $lesson->feedbackless_reply_count() }} replies awaiting feedback">{{ $lesson->feedbackless_reply_count() }} new</span>@endif
              </b-button>
            @empty
              <section class="section is-medium has-background-light has-text-centered">
                No lessons have been added yet.
              </section>
            @endforelse

          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
