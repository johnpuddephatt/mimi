@extends('layouts.app')

@section('content')
<section class="section is-medium">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-6-tablet is-5-desktop is-4-widescreen">
        <div class="box">
          <h3 class="title has-text-centered">Modifica LESSON ✏️</h3>
          <p class="subtitle has-text-centered">You can edit this lesson below</p>

          @if ($errors->any())
          <ul class="notification is-danger">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
          @endif



          <form action="/admin/lesson/update/{{ $lesson->id }}" method="post">
            <div class="field">
              <label for="title" class="label">Title</label>
              <div class="control">
                <input name="title" value="{{ old('name', $lesson->title) }}" type="text" placeholder="e.g. Summer 2020" class="input" required>
              </div>
            </div>
            <div class="field">
              <label for="description" class="label">Description</label>
              <div class="control">
                <textarea name="description" class="textarea" rows="5">{{ old('description', $lesson->description) }}</textarea>
              </div>
            </div>
            <div class="field">
              <b-button tag="a" href="{{ route('lesson.update', ['lesson' => $lesson->id ]) }}" type="is-primary" class="is-fullwidth">
                Update
              </b-button>
            </div>
          </form>

        </div>
      </div>
    </div>
</section>
@endsection
