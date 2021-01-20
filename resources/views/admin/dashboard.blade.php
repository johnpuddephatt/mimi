@extends('layouts.app')

@section('content')
<section class="section is-medium">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
        <div class="box">
          <h3 class="title has-text-centered">Ciao, capo 👑️</h3>
          <p class="subtitle has-text-centered">Manage courses here</p>
          <nav class="panel is-shadowless is-bordered">

            @forelse($courses as $course)
            <div class="panel-block is-justify-between">
              <p>{{ $course->title }}
                @if($course->archived)
                  <span class="tag is-light">Archived</span>
                @endif
              </p>

              <b-field>
                <p class="control">
                  <b-button tag="a" href="{{ route('course.edit', ['course'=> $course->id]) }}">
                    Edit
                  </b-button>
                </p>
              </b-field>
            </div>
            @empty
              <section class="section is-medium has-background-light has-text-centered">
                No courses to show you.
              </section>
            @endforelse
            <div class="panel-block">
              <b-button type="is-primary" tag="a" href="{{ route('course.new') }}" class="is-fullwidth">
                Add new course
              </b-button>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
