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
              {{ $course->title }}

              <b-field>
                <p class="control">
                  <b-button tag="a" href="{{ route('course.edit', ['course'=> $course->id]) }}">
                    Edit
                  </b-button>
                </p>
                {{-- <p class="control">
                  <b-dropdown position="is-bottom-left">
                    <b-button slot="trigger">
                      <b-icon icon="menu-down"></b-icon>
                    </b-button>
                    <b-dropdown-item>Delete</b-dropdown-item>
                    <b-dropdown-item>Copy invite link</b-dropdown-item>
                  </b-dropdown>
                </p> --}}
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
