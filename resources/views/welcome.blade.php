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

            <recorder></recorder>

            @if(count($videos))
               <ul>
                  @foreach($videos as $video)
                     @if($video->playlist)
                        <video-player source="{{ $video->playlist }}"></video-player>
                     @else
                        Coming soon
                     @endif
                  @endforeach
               </ul>
            @else
               <div class="alert alert-info">No videos to show you yet</div>
            @endif
            </div>
         </div>
      </div>
   </div>
@endsection
