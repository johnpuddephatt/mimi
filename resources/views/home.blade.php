@extends('layouts.app')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-6-tablet is-5-desktop is-4-widescreen">
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
                href="{{ $course->link() }}"
                class="is-justify-between"
                icon-right="arrow-right"
                expanded
                outlined>
                  {{ $course->title }}
              </b-button>
            @empty
              UH OH. You're not enrolled in anything.
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
