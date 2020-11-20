@extends('layouts.app')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Statistics 📈</h3>
            <p class="subtitle has-text-centered">With great power comes great responsibility</p>
            <teacher-monthly-chart></teacher-monthly-chart>
            <br><br>
            <replies-monthly-chart></replies-monthly-chart>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
