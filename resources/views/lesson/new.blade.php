@extends('layouts.app')

@section('content')
<section class="section is-medium">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen">
        <lesson-form mode="new" :course_id="{{ $course->id }}" :lesson_count="{{ $lesson_count }}"></lesson-form>
      </div>
    </div>
</section>
@endsection
