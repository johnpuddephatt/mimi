@extends('layouts.app')

@section('content')
 <section class="section is-medium">
   <div class="container">
      <div class="columns is-centered">
        <div class="column is-6-tablet is-5-desktop is-5-widescreen">
          <registration-form admin="{{ $admin ?? null }}" course="{{ $course ?? null }}"></registration-form>
        </div>
    </div>
  </section>
@endsection
