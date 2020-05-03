@extends('layouts.app')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-6-tablet is-5-desktop is-4-widescreen">
          <div class="box">
            <h3 class="title has-text-centered">The conversation room</h3>
            <p class="subtitle has-text-centered">{{ $lesson->title}}</p>
            <h3>Instruzioni 📝 </h3>
            <div>{{ $lesson->instructions }}</div>
            <video-player class="has-square-media" source="{{ $lesson->video->playlist }}" type="application/x-mpegURL"></video-player>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
