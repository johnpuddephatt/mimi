@extends('layouts.app')

@section('content')
<section class="section is-medium">
  <div class="container">
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen">
        <form action="/admin/course/create" method="post" class="box">
          @csrf
          <h3 class="title has-text-centered">Creare corso 🆕</h3>
          <p class="subtitle has-text-centered">You can create a new course below</p>

          @if ($errors->any())
            <ul class="notification is-danger">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          @endif

          <div class="field">
            <label for="title" class="label">Title</label>
            <div class="control">
              <input name="title" value="{{ old('title')}}" type="text" placeholder="e.g. Summer 2020" class="input">
            </div>
          </div>
          <div class="field">
            <label for="description" class="label">Description</label>
            <div class="control">
              <textarea name="description" value="{{ old('description')}}" class="textarea" rows="3"></textarea>
            </div>
          </div>
          <div class="field">
            <input class="button is-primary is-fullwidth" type="submit" value="Create">
            <p class="help has-text-centered">You can add lessons and invite people after creation.</p>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
