@extends('layouts.app')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-6-tablet is-5-desktop is-4-widescreen">
          <div class="box">
            <h3 class="title has-text-centered">{{ $course->title}} </h3>
            <p class="subtitle has-text-centered">{{ $course->description}}</p>

            @forelse ($course->lessons as $lesson)

              <b-button
                tag="a"
                size="is-medium"
                href="{{ route('lesson.single', ['lesson' => $lesson->id ]) }}"
                class="is-justify-between"
                icon-right="arrow-right"
                expanded
                outlined>
                  {{ $lesson->title }}
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
