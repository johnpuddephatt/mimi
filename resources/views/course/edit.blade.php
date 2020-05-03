@extends('layouts.app')

@section('content')
<section class="section is-medium">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-6-tablet is-5-desktop is-4-widescreen">
        <div class="box">
          <h3 class="title has-text-centered">Modifica corso ✏️</h3>
          <p class="subtitle has-text-centered">You can edit this course below</p>

          @if ($errors->any())
          <ul class="notification is-danger">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
          @endif


          <b-tabs position="is-centered" type="is-toggle">
            <b-tab-item label="Overview">
              <form action="/admin/course/update/{{ $course->id }}" method="post">
                <div class="field">
                  <label for="title" class="label">Title</label>
                  <div class="control">
                    <input name="title" value="{{ old('name', $course->title) }}" type="text" placeholder="e.g. Summer 2020" class="input" required>
                  </div>
                </div>
                <div class="field">
                  <label for="description" class="label">Description</label>
                  <div class="control">
                    <textarea name="description" class="textarea" rows="3">{{ old('description', $course->description) }}</textarea>
                  </div>
                </div>
                <div class="field">
                  <b-button tag="a" href="{{ route('course.update', ['course' => $course->id ]) }}" type="is-primary" class="is-fullwidth">
                    Update
                  </b-button>
                </div>
              </form>

            </b-tab-item>
            <b-tab-item label="Lessons">
              @forelse($course->lessons as $lesson)
                We have lessons!
              @empty
                <section class="section is-medium has-background-light has-text-centered">
                  Lessons will appear here.
                </section>
              @endforelse
              <a class="button is-fullwidth" href="{{ route('lesson.new', ['course' => $course->id]) }}">Add new lesson</a>

            </b-tab-item>
            <b-tab-item label="Enrolments">

              @forelse($course->users as $user)
                We have people!
              @empty
                <section class="section is-medium has-background-light has-text-centered">
                  Enrolled users will appear here.
                </section>
              @endforelse
              <div class="notification">
                Get invitation link
              </div>
            </b-tab-item>
          </b-tabs>

        </div>
      </div>
    </div>
</section>
@endsection
