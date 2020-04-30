@extends('layouts.app')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-8-tablet is-7-desktop is-6-widescreen">
          <div class="box">
            <h3 class="title has-text-centered">Ciao, capo 👑️</h3>
            <p class="subtitle has-text-centered">Manage courses and users here</p>

<h4>Courses</h4>


              <nav class="panel">
                <p class="panel-heading">Courses</p>
                <p class="panel-tabs">
                  <a class="is-active">Active</a>
                  <a>Expired</a>
                </p>
                @forelse($courses as $course)
                  <div class="panel-block" >
                    {{ $course->title }}
                    <div class="field is-grouped">
                      <b-field>
                        <p class="control">
                          <b-button type="is-primary">
                             Edit
                          </b-button>
                        </p>
                        <p class="control">
                          <b-dropdown position="is-bottom-left">
                            <b-button type="is-primary" slot="trigger">
                              <b-icon icon="menu-down"></b-icon>
                            </b-button>
                            <b-dropdown-item>Delete</b-dropdown-item>
                            <b-dropdown-item>Copy invite link</b-dropdown-item>
                          </b-dropdown>
                        </p>
                      </b-field>
                  </div>
                  </div>
                @empty
                <section class="section">
                  No courses to show you.
                </section>
                @endforelse
              </nav>

          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
