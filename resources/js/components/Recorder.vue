<template>

<div class="card">
   <h5 class="card-header">Record video
      <div v-if="devices.length > 1" class="float-right">
         <select v-model="deviceId">
            <option v-for="device in devices" :key="device.deviceId" :value="device.deviceId">{{ device.label | removeParentheses }}</option>
         </select>&nbsp;
      </div>
   </h5>

   <div v-if="permission == 'granted'" class="card-body webcam-wrapper">
      <video-player v-if="recordingBlob" :source="recordingBlobUrl"></video-player>
      <vue-web-cam v-show="!recordingBlob" ref="webcam" :device-id="deviceId" width="100%" @started="onStarted" @stopped="onStopped" @error="onError" @cameras="onCameras" @camera-change="onCameraChange" />
   </div>

   <div v-else-if="permission == 'denied'" class="card-body">
      <h3>Whoops.</h3>
      <p>We don't seem to have access to your camera, you may need to take a look at your settings to find out how to enable it.</p>
      <button class="btn btn-primary" @click="requestMedia">Retry</button>

   </div>

   <div v-else-if="permission == 'unsupported'" class="card-body">
      <p>This device doesn't support requestMedia, so we can’t access the webcam directly. Instead we'll show a file upload button, which give access to the camera on lots of devices!</p>
      <input type="file" name="video" accept="video/*" capture="user" id="video-input">
   </div>

   <div v-else class="card-body">
      <h3>Welcome</h3>
      <p>You’ll need this site permission to access your camera.</p>
      <p>If you’re having problems doing this, <a href="#">get help here</a></p>
      <button class="btn btn-primary" @click="requestMedia">Start</button>
   </div>
   <div class="card-footer" v-if="isStarted && !recordingBlob">
      <button type="button" class="btn btn-success" @click="onCapture">Capture Photo</button>
      <button type="button" class="btn btn-danger" @click="onRecordToggle">{{ isRecording ? 'Stop recording' : 'Start recording' }}</button>
   </div>

   <div class="card-footer" v-if="recordingBlob">
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
         recordingBlob: '',
         mediaRecorder: null,
         deviceId: null,
         devices: [],
         permission: '',
      };
   },
   computed: {
      device: function() {
         return this.devices.find(n => n.deviceId === this.deviceId);
      },
      recordingBlobUrl: function() {
         return URL.createObjectURL(this.recordingBlob);
      }
   },
   watch: {
      camera: function(id) {
         this.deviceId = id;
      },
      devices: function() {
         // Once we have a list select the first one
         const [first, ...tail] = this.devices;
         this.deviceId = localStorage.getItem('deviceId');
         if (first && !this.deviceId) {
            this.deviceId = first.deviceId;
         }
      }
   },
   created() {
      this.checkPermissions();
   },
   methods: {
      checkPermissions() {
         try {
            var vm = this;
            navigator.permissions.query({
               name: 'camera'
            })
            .then(function(result) {
               vm.permission = result.state;
               result.onchange = function() {
                  vm.permission = result.state;
               }
            });
         }
         catch(error) {
            this.permission = 'unknown';
            console.warn(error);
         }
      },
      onCapture() {
         this.img = this.$refs.webcam.capture();
      },
      onStarted(stream) {
         this.isStarted = true;

         console.log("On Started Event", stream);

         const options = {
            mimeType: 'video/webm'
         };
         const recordedChunks = [];

         this.mediaRecorder = new MediaRecorder(stream, options);

         this.mediaRecorder.addEventListener('dataavailable', (e) => {
            if (e.data.size > 0) {
               recordedChunks.push(e.data);
            }
            if(this.isRecording === true && this.shouldStopRecording === true) {
             this.mediaRecorder.stop();
             this.isRecording = false;
            }
         });

         this.mediaRecorder.addEventListener('stop', () => {
            this.recordingBlob = new Blob(recordedChunks);
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
         console.log("On Error Event", error);
      },
      onRecordToggle() {
         if(this.isRecording) {
            this.shouldStopRecording = true;
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
         this.recordingBlob = null;
      },
      onUpload() {
         const data = new FormData();
         data.append('title', 'Vue');
         data.append('video', this.recordingBlob);
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
            });1
      },
      updateProgress(percentage) {
         this.progress = percentage;
      },
      requestMedia() {
         try {
            const stream = navigator.mediaDevices.getUserMedia({video: true});
         }
         catch(error) {
            this.permission = 'unsupported';
            console.log(error);
         }
      }
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
