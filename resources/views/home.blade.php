@extends('layouts.app')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Corsi 🗂️</h3>
            @if(\Auth::user()->is_admin)
              <p class="subtitle has-text-centered">Hi Admin! Here’s all active courses</p>
            @else
              <p class="subtitle has-text-centered">You’re enrolled on the courses below</p>
            @endif

            @forelse($courses as $course)
              <b-button
                tag="a"
                size="is-medium"
                href="{{ route('course.single', ['course' => $course->id ]) }}"
                class="is-justify-between"
                icon-right="arrow-right"
                expanded
                outlined>
                  {{ $course->title }}

                  @if(Auth::user()->is_admin && $course->feedbackless_reply_count())<span class="tag is-primary ml-4" title="{{ $course->feedbackless_reply_count() }} replies awaiting feedback">{{ $course->feedbackless_reply_count() }} new</span>@endif

              </b-button>
            @empty
              <section class="section is-medium has-background-light has-text-centered">
                @if(Auth::user()->is_admin)
                  <a class="button" href="{{ route('course.new') }}">Add the first course</a>
                @else
                  <p>You’re not enrolled in any courses.</p>
                @endif

              </section>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
