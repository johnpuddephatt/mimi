<template>

<div class="card">
   <h5 class="card-header">Record video
      <div v-if="devices.length > 1" class="float-right">
         <select v-model="deviceId">
            <option v-for="device in devices" :key="device.deviceId" :value="device.deviceId">{{ device.label | removeParentheses }}</option>
         </select>&nbsp;
      </div>
   </h5>

   <div v-if="permission == 'granted'" class="card-body p-0">
      <video v-if="recordingBlob" :src="recordingBlobUrl" width="100%" controls="true"></video>
      <vue-web-cam v-show="!recordingBlob" ref="webcam" :device-id="deviceId" width="100%" @started="onStarted" @stopped="onStopped" @error="onError" @cameras="onCameras" @camera-change="onCameraChange" />
   </div>

   <div v-else-if="permission == 'denied'" class="card-body">
      <h3>Whoops.</h3>
      <p>We don't have access to your camera, you may need to take a look at your settings to find out how to enable it.</p>
   </div>

   <div v-else-if="permission == 'prompt'" class="card-body">
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
         shouldStopRecording: false,
         recordingBlob: '',
         mediaRecorder: null,
         deviceId: null,
         devices: [],
         permission: 'unknown'
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
         var recordingBlob = this.recordingBlob;
         this.mediaRecorder = new MediaRecorder(stream, options);

         this.mediaRecorder.addEventListener('dataavailable', (e) => {
            if (e.data.size > 0) {
               recordedChunks.push(e.data);
            }
            if(this.isRecording && this.shouldStopRecording === true) {
             console.log('media recorder stopped');
             this.mediaRecorder.stop();
            }
         });

         this.mediaRecorder.addEventListener('stop', () => {
            this.isRecording = false;
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
          headers: {'Content-Type': `multipart/form-data; boundary=${data._boundary}`}})
             .then(res => {
                 console.log(res);
             }).catch(err => {
             console.log(err)
         });
      },
      requestMedia() {
         const stream = navigator.mediaDevices.getUserMedia({video: true});
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
   video {
      display: block;
      object-fit: cover
   }
</style>
