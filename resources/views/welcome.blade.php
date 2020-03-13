@extends('layouts.app')

@section('content')
   <div id="app">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-6">

               @if (isset($errors) && count($errors))
                  @foreach($errors->all() as $error)
                      <div class="alert alert-warning">{{ $error }} </div>
                  @endforeach
               @endif

               <video-recorder></video-recorder>
               <br>
               <video-playlist></video-playlist>
            </div>
         </div>
      </div>
   </div>
@endsection
