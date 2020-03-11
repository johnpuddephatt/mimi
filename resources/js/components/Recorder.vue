<template>

<div class="card">
   <h5 class="card-header">
      Record video
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

   <div v-else-if="recordingData" class="card-body webcam-wrapper">
      <video-player :source="recordingUrl" :type="mimeType"></video-player>
   </div>

   <div v-else-if="cameraType" class="card-body" :class="cameraType == 'mediaRecorder' ? 'webcam-wrapper': ''">
      <vue-web-cam v-if="cameraType == 'mediaRecorder'" ref="webcam" :device-id="deviceId" width="640" height="480" @started="onStarted" @stopped="onStopped" @error="onError" @cameras="onCameras" @camera-change="onCameraChange" />
      <div v-else>
         <p>This device doesn't support mediaRecorder/requestMedia, so we can’t access the webcam directly. Instead we'll show a file upload button, which give access to the camera on lots of devices!</p>
         <input @change="onFileInputChange" type="file" name="video" accept="video/webm,video/x-matroska,video/mp4,video/x-m4v,video/*" capture="user">
      </div>
   </div>

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
         recordingData: false,
         recordingMimeType: '',
         mediaRecorder: null,
         deviceId: null,
         devices: [],
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
      },
      mimeType: function() {
         switch (this.recordingMimeType) {
            case 'video/ogg':
            case 'video/mp4':
            case 'video/webm':
            break;
            default: false;
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
   },
   methods: {

      openCamera() {
         if(window.navigator.mediaDevices && window.navigator.mediaDevices.getUserMedia && window.MediaRecorder) {
            this.cameraType = 'mediaRecorder';
         }
         else {
            this.cameraType = 'fallback';
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
         this.recordingMimeType = this.mediaRecorder.mimeType;

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
         this.recordingData = false;
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
         this.recordingMimeType = evt.target.files[0].type;
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
