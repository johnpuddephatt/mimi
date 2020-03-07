@extends('layouts.app')

@section('content')

            <div class="content">
                <div class="title m-b-md">
                    Videos
                </div>

            <form id="form" method="POST" action="/upload" enctype="multipart/form-data">
                @csrf
                <video id="player" width="640" height="480" autoplay></video>
                <input type="hidden" name="title" value="My video">
                <input type="file" name="video" accept="video/*" capture="user" id="video-input">
                {{-- <input type="submit"> --}}


                <div class="field has-addons">
                  <p class="control">
                    <button class="button" id="start">
                      <span class="icon is-small">
                        <i class="fas fa-video"></i>
                      </span>
                      <span>Record</span>
                    </button>
                  </p>
                  <p class="control">
                    <button class="button" id="stop">
                      <span class="icon is-small">
                        <i class="fas fa-stop-circle"></i>
                      </span>
                      <span>Stop</span>
                    </button>
                  </p>
                  <p class="control">
                    <button class="button" id="download">
                      <span class="icon is-small">
                        <i class="fas fa-download"></i>
                      </span>
                      <span>Download</span>
                    </button>
                  </p>
                </div>

                <script>
                  var input = document.getElementById('video-input');
                  var player = document.getElementById('player');
                  var form = document.getElementById('form');

                  // input.addEventListener('change', function(e) {
                  //   var file = e.target.files[0];
                  //   // Do something with the video file.
                  //   player.src = URL.createObjectURL(file);
                  // });


                    let shouldStop = false;
                    let stopped = false;
                    const downloadButton = document.getElementById('download');
                    const startButton = document.getElementById('start');
                    const stopButton = document.getElementById('stop');

                    stopButton.addEventListener('click', function(e) {
                      e.preventDefault();
                      shouldStop = true;
                    })

                    downloadButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        form = new FormData(),
                        request = new XMLHttpRequest();
                        form.append("video",window.blobby);
                        form.append("_token", "{{ csrf_token() }}");
                        form.append("title","Ajax");
                        request.open(
                            "POST",
                            "/upload",
                            true
                        );
                        request.send(form);
                    })

                    var handleSuccess = function(stream) {
                      player.srcObject = stream;
                      const options = {mimeType: 'video/webm'};
                      const recordedChunks = [];
                      const mediaRecorder = new MediaRecorder(stream, options);

                      mediaRecorder.addEventListener('dataavailable', function(e) {
                        if (e.data.size > 0) {
                          recordedChunks.push(e.data);
                        }

                        if(shouldStop === true && stopped === false) {
                          mediaRecorder.stop();
                          stopped = true;
                        }
                      });

                      mediaRecorder.addEventListener('stop', function() {
                        window.blobby = new Blob(recordedChunks);
                        // input.files = URL.createObjectURL(window.blobby);
                      });

                      startButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        mediaRecorder.start(5000);
                      })
                    };

                    navigator.mediaDevices.getUserMedia({ audio: true, video: true })
                        .then(handleSuccess);

                </script>
            </form>
<br><br><br>
            @if(count($videos))
                <ul>
                    @foreach($videos as $video)
                        <li>{{ $video->title }}</li>
                    @endforeach
                </ul>
            @else
                No videos to show you yet
            @endif

            @if (isset($errors) && count($errors))
                 <p>There were {{count($errors->all())}} Error(s)<p>
                            <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }} </li>
                        @endforeach
                    </ul>

            @endif

            </div>
@endsection
