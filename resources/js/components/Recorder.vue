<template>

<div class="card">
   <h5 class="card-header">Record video
      <div v-if="devices.length > 1" class="float-right">
         <select v-model="deviceId">
            <option v-for="device in devices" :key="device.deviceId" :value="device.deviceId">{{ device.label | removeParentheses }}</option>
         </select>&nbsp;
      </div>
   </h5>

   <div v-if="errorMessage" class="card-body">
      <h2>An error has occured</h2>
      <p>Unfortunately we have not been able to access your camera. This might be because you haven’t given us permission to, or because your phone or computer doesn’t support doing this.</p>
      <div class="alert alert-warning">
         {{ errorMessage }}
      </div>
   </div>
   <div v-else-if="cameraType == 'mediaRecorder'" class="card-body webcam-wrapper">
      <video-player v-if="recordingData" :source="recordingUrl" type="video/MP4"></video-player>
      <vue-web-cam v-show="!recordingData" ref="webcam" :device-id="deviceId" width="640" height="480" @started="onStarted" @stopped="onStopped" @error="onError" @cameras="onCameras" @camera-change="onCameraChange" />
   </div>
   <div v-else-if="cameraType == 'fallback'" class="card-body" :class="recordingData ? 'webcam-wrapper': ''">
      <video-player v-if="recordingData" :source="recordingUrl" type="video/MP4"></video-player>
      <div v-else>
         <p>This device doesn't support mediaRecorder/requestMedia, so we can’t access the webcam directly. Instead we'll show a file upload button, which give access to the camera on lots of devices!</p>
         <input @change="onFileInputChange" type="file" name="video" accept="video/*" capture="user">
      </div>
   </div>

   <!-- <div v-else-if="permission == 'denied'" class="card-body">
      <h3>Whoops.</h3>
      <p>We don't seem to be able to access your camera, you may need to take a look at your settings to find out how to enable it.</p>
   </div>

   <div v-else-if="permission == 'unsupported'" class="card-body">
      <p>This device doesn't support requestMedia, so we can’t access the webcam directly. Instead we'll show a file upload button, which give access to the camera on lots of devices!</p>
      <input type="file" name="video" accept="video/*" capture="user" id="video-input">
   </div> -->

   <div v-else class="card-body">
      <h3>Welcome</h3>
      <button class="btn btn-primary" @click="openCamera">Start</button>
   </div>
   <div class="card-footer" v-if="isStarted && !recordingData">
      <button type="button" class="btn btn-success" @click="onCapture">Capture Photo</button>
      <button type="button" class="btn btn-danger" @click="onRecordToggle">{{ isRecording ? 'Stop recording' : 'Start recording' }}</button>
   </div>

   <div class="card-footer" v-if="recordingData">
      <button type="button" class="btn btn-success" @click="onUpload">Upload</button>
      <button type="button" class="btn btn-danger" @click="onRestart">Restart</button>
      {{progress}}%
   </div>

   <div v-if="img">
      <h2>Captured Image</h2>
      <figure class="figure">
         <img :src="img" class="img-responsive" />
      </figure>
   </div>
</div>
</template>

<script>
import VueWebCam from "./WebCam";

export default {
   components: {
      'vue-web-cam': VueWebCam,
   },
   data() {
      return {
         img: null,
         isStarted: false,
         isRecording: false,
         progress: 0,
         shouldStopRecording: false,
         recordingData: '',
         mediaRecorder: null,
         deviceId: null,
         devices: [],
         // permission: '',
         // permissionsApiSupported: true,
         cameraType: false,
         errorMessage: '',
      };
   },
   computed: {
      device: function() {
         return this.devices.find(n => n.deviceId === this.deviceId);
      },
      recordingUrl: function() {
         if(this.recordingData) {
            return URL.createObjectURL(this.recordingData);
         }
      }
   },
   watch: {
      camera: function(id) {
         this.deviceId = id;
      },
      devices: function() {
         // Once we have a list select the first one
         const [first, ...tail] = this.devices;
         if(this.devices.indexOf(localStorage.getItem('deviceId'))) {
            this.deviceId = localStorage.getItem('deviceId');
         }
         if (first && !this.deviceId) {
            this.deviceId = first.deviceId;
         }
      }
   },
   created() {
      // this.checkPermissions();
   },
   methods: {
      // checkPermissions() {
      //
      //       if(window.navigator.permissions) {
      //          var vm = this;
      //          window.navigator.permissions.query({
      //             name: 'camera'
      //          })
      //          .then(function(result) {
      //             vm.permission = result.state;
      //             result.onchange = function() {
      //                vm.permission = result.state;
      //             }
      //          }).catch(err => {
      //             console.warn(error);
      //          });
      //       }
      //       else {
      //          console.log('permissions api not available.');
      //          this.permission = 'unknown';
      //          this.permissionsApiSupported = false;
      //       }
      // },

      openCamera() {
         if(window.navigator.mediaDevices &&window.navigator.mediaDevices.getUserMedia && window.MediaRecorder) {
            this.cameraType = 'fallback';
         }
         else {
            this.cameraType = 'mediaRecorder';
         }

      },
      onCapture() {
         this.img = this.$refs.webcam.capture();
      },
      onStarted(stream) {
         this.isStarted = true;

         console.log("On Started Event", stream);

         const options = {
            // mimeType: 'video/webm'
         };
         const recordedChunks = [];

         this.mediaRecorder = new MediaRecorder(stream, options);
         console.log('Mime:',this.mediaRecorder.mimeType);

         this.mediaRecorder.addEventListener('dataavailable', (e) => {
            console.log(this.mediaRecorder.state);
            if (e.data.size > 0) {
               recordedChunks.push(e.data);
            };
            if(this.shouldStopRecording && this.isRecording && this.mediaRecorder.state == 'recording') {
               this.mediaRecorder.stop();
            }
         });

         this.mediaRecorder.addEventListener('stop', () => {;
            this.isRecording = false;
            this.recordingData = new Blob(recordedChunks);
         });
      },
      onStopped(stream) {
         console.log("On Stopped Event", stream);
      },
      onStop() {
         console.log("On stop");
         this.$refs.webcam.stop();
      },
      onStart() {
         console.log("On start");
         this.$refs.webcam.start();
      },
      onError(error) {
         this.errorMessage = error;
         console.log("On Error Event", error);
      },
      onRecordToggle() {
         if(this.isRecording) {
            this.shouldStopRecording = true;
            // Timeout fallback for Safari, which only fires the dataavailable event once on stop
            setTimeout(() => {
               if(this.mediaRecorder.state == 'recording') {
                  this.mediaRecorder.stop();
               }
            }, 1000);
         }
         else {
            this.isRecording = true;
            this.shouldStopRecording = false;
            this.mediaRecorder.start(1000);
            console.log('media recorder started');
         }
      },
      onCameras(cameras) {
         this.devices = cameras;
      },
      onCameraChange(deviceId) {
         this.deviceId = deviceId;
         localStorage.setItem('deviceId', deviceId);
         console.log("On Camera Change Event", deviceId);
      },
      onRestart() {
         this.recordingData = null;
      },
      onUpload() {
         const data = new FormData();
         data.append('title', 'Vue');
         data.append('video', this.recordingData);
         axios.post('/upload', data, {
            headers: {'Content-Type': `multipart/form-data; boundary=${data._boundary}`},
            onUploadProgress: progressEvent => this.updateProgress(Math.round( (progressEvent.loaded * 100) / progressEvent.total ))
         })
            .then(res => {
               console.log(res);
               this.progress = 0;
            }).catch(err => {
               console.log(err);
               this.progress = 0;
            });
      },
      updateProgress(percentage) {
         this.progress = percentage;
      },
      onFileInputChange(evt) {
         this.recordingData = evt.target.files[0];
      },
      // requestMedia() {
      //    try {
      //       const stream = navigator.mediaDevices.getUserMedia({video: true, audio: true}).then(res => {
      //          console.log(res);
      //          this.permission = 'granted';
      //       }).catch(err => {
      //          console.log(err);
      //          this.permission = 'denied';
      //       });
      //
      //    }
      //    catch(error) {
      //       this.permission = 'unsupported';
      //       console.log(error);
      //    }
      // }
   },
   filters: {
      removeParentheses: function(value) {
         return value.replace(/\([^()]*\)/g, '');
      }
   }
};
</script>


<style>

   .webcam-wrapper {
      padding-top: 100%;
      position: relative;
   }

   .webcam-wrapper video {
      position: absolute;
      margin: 0;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      height: 100%;
      width: 100%;
      display: block;
      object-fit: cover
   }
</style>
