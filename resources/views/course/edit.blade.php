@extends('layouts.app')

@section('content')
<section class="section is-medium">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen">
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
                @method('PUT')
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
              <nav class="panel is-shadowless">
                @forelse($course->lessons as $lesson)
                  <div class="panel-block is-justify-between">
                    <span><strong>{{ $lesson->number }}.&nbsp;</strong> {{ $lesson->title }}</span>
                    <b-button size="is-small" tag="a" href="{{ route('lesson.edit', ['course' => $course->id, 'lesson'=> $lesson->id]) }}">
                      Edit
                    </b-button>
                  </div>
                @empty
                  <section class="section is-medium has-background-light has-text-centered">
                    Lessons will appear here once added.
                  </section>
                @endforelse
              </nav>
              <b-button expanded tag="a" type="is-primary" href="{{ route('lesson.new', ['course' => $course->id]) }}">Add new lesson</b-button>

            </b-tab-item>
            <b-tab-item label="Enrolments">

              @forelse($course->users as $user)
                <div class="panel-block is-justify-between">
                  <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                </div>
              @empty
                <section class="section is-medium has-background-light has-text-centered">
                  Users will appear here once enrolled.
                </section>
              @endforelse
              <div class="notification">
                <h4>Invite people to this course</h4>
                <input id="course-invite-link" class="input" type="text" value="{{ route('register') . '?course=' . $course->hash() }}">
              </div>
            </b-tab-item>
          </b-tabs>

        </div>
      </div>
    </div>
</section>
@endsection
