@extends('layouts.app')

@section('content')
<section class="section is-medium">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-6-tablet is-5-desktop is-4-widescreen">
        <div class="box">
          <h3 class="title has-text-centered">Creare lezione 🆕</h3>
          <p class="subtitle has-text-centered">Setup a new lesson below</p>

          <lesson-form :course_id="{{ $course->id }}"></lesson-form>

        </div>
      </div>
    </div>
</section>
@endsection
