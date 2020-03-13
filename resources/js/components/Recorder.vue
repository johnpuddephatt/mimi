<template>

   <div v-if="errorMessage" class="card">
      <h5 class="card-header">Error</h5>
      <div class="card-body">
         <p>Unfortunately we have not been able to access your camera. This might be because you haven’t given us permission to, or because your phone or computer doesn’t support doing this.</p>
         <div class="alert alert-warning">
            {{ errorMessage }}
         </div>
      </div>
   </div>

   <div class="card" v-else-if="uploadComplete">
      <h5 class="card-header">Upload complete</h5>
      <div class="card-body">
         Your video has been uploaded. Once it’s ready it will appear below!
      </div>
      <div class="card-footer">
         <button type="button" class="btn btn-danger" @click="onRestart">Start again</button>
      </div>
   </div>

   <div class="card" v-else-if="thumbnailData">
      <h5 class="card-header">Review photo and upload</h5>
      <div class="card-body has-square-media">
         <img :src="thumbnailUrl" />
      </div>
      <div class="card-footer">
         <button type="button" class="btn btn-danger" @click="onRetakePhoto">Retake photo</button>
         <button type="button" class="btn btn-success" @click="onUpload">Upload</button>
         {{progress}}%
      </div>
   </div>

   <div class="card" v-else-if="isReadyToCapturePhoto">
      <h5 class="card-header">Take your photo</h5>
      <div class="card-body" :class="cameraType == 'mediaRecorder' ? 'has-square-media': ''">
         <vue-web-cam v-if="cameraType == 'mediaRecorder'" ref="webcamPhoto" :device-id="deviceId" width="640" height="480" @photo="onPhotoReady" @error="onError" @cameras="onCamerasPhoto" @camera-change="onCameraChange" />
         <div v-else>
            <p class="alert alert-warning">This device doesn't support mediaRecorder/requestMedia, so we can’t access the webcam directly. Instead we'll show a file upload button, which gives access to the camera on most devices!</p>
            <input @change="onPhotoInputChange" type="file" name="photo" accept="image/*" capture="user">
         </div>
      </div>
      <div class="card-footer">
         <button type="button" class="btn btn-danger" @click="onRestart">Start again</button>
         <button type="button" class="btn btn-success" @click="onCapturePhoto">Take Photo</button>
      </div>
   </div>

   <div class="card" v-else-if="recordingData">
      <h5 class="card-header">Review video <small class="float-right">{{recordingMimeType}}</small></h5>
      <div class="card-body has-square-media">
         <video-player :source="recordingUrl" :type="mimeType"></video-player>
      </div>
      <div class="card-footer">
         <button type="button" class="btn btn-danger" @click="onRestart">Re-record video</button>
         <button type="button" class="btn btn-success" @click="onReadyToCapturePhoto">Next</button>
      </div>
   </div>

   <div class="card" v-else-if="cameraType" >
      <h5 class="card-header">Record your video
         <div v-if="devices.length > 1" class="float-right">
            <select v-model="deviceId">
               <option v-for="device in devices" :key="device.deviceId" :value="device.deviceId">{{ device.label | removeParentheses }}</option>
            </select>&nbsp;
         </div>
      </h5>
      <div class="card-body" :class="cameraType == 'mediaRecorder' ? 'has-square-media': ''">
         <vue-web-cam v-if="cameraType == 'mediaRecorder'" ref="webcamVideo" :device-id="deviceId" width="640" height="480" @cameras="onCamerasVideo" @started="onStartedVideo" @error="onError" @camera-change="onCameraChange" />
         <div v-else>
            <p>This device doesn't support mediaRecorder/requestMedia, so we can’t access the webcam directly. Instead we'll show a file upload button, which give access to the camera on lots of devices!</p>
            <input @change="onVideoInputChange" type="file" name="video" accept="video/webm,video/x-matroska,video/mp4,video/x-m4v,video/*" capture="user">
         </div>
      </div>
      <div class="card-footer" v-if="isStarted">
         <button type="button" class="btn btn-danger" @click="onRecordToggle">{{ isRecording ? 'Stop recording' : 'Start recording' }}</button>
      </div>
   </div>

   <div v-else class="card">
      <h5 class="card-header">Welcome</h5>
      <div class="card-body">
         <button class="btn btn-primary" @click="openCamera">Start</button>
      </div>
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
         isStarted: false,
         isRecording: false,
         uploadComplete : false,
         progress: 0,
         shouldStopRecording: false,
         isReadyToCapturePhoto: false,
         recordingData: false,
         thumbnailData: false,
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
      thumbnailUrl: function() {
         if(this.thumbnailData) {
            return URL.createObjectURL(this.thumbnailData);
         }
      },
      mimeType: function() {
         switch (this.recordingMimeType) {
            case 'video/ogg':
               return 'video/ogg';
            case 'video/mp4':
            case 'video/x-matroska':
            case 'video/x-matroska;codecs=avc1,opus':
               return 'video/mp4';
            case 'video/webm':
               return 'video/webm';
            case 'video/quicktime':
               return 'video/quicktime';
            break;
            default: '';
         }
      }
   },
   watch: {
      camera: function(deviceId) {
         this.deviceId = deviceId;
      },
      devices: function(devices) {
         // Once we have a list select the first one
         if(devices.length) {
            const [first, ...tail] = this.devices;
            if(this.devices.indexOf(localStorage.getItem('deviceId'))) {
               this.deviceId = localStorage.getItem('deviceId');
            }
            if (first && !this.deviceId) {
               this.deviceId = first.deviceId;
            }
         }
      },
      recordingData: function() {
         console.log('recordingData has changed');
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
      onCapturePhoto() {
         this.$refs.webcamPhoto.capture();
      },
      onPhotoReady(blob) {
         this.thumbnailData = blob;
      },
      onRetakePhoto() {
         this.thumbnailData = false;
      },
      onReadyToCapturePhoto() {
         this.isReadyToCapturePhoto = true;
      },
      onStartedVideo(stream) {
         this.isStarted = true;

         const options = {
            mimeType: 'video/webm'
         };
         const recordedChunks = [];

         this.mediaRecorder = new MediaRecorder(stream, options);
         this.recordingMimeType = this.mediaRecorder.mimeType;

         this.mediaRecorder.addEventListener('dataavailable', (e) => {
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
      onError(error) {
         this.errorMessage = error;
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
         }
      },
      onCamerasPhoto(cameras) {
         this.devices = cameras;
         this.$refs.webcamPhoto.start();
      },
      onCamerasVideo(cameras) {
         this.devices = cameras;
         this.$refs.webcamVideo.start();
      },
      onCameraChange(deviceId) {
         this.deviceId = deviceId;
         localStorage.setItem('deviceId', deviceId);
      },
      onRestart() {
         this.recordingData = false;
         this.progress = 0;
         this.isReadyToCapturePhoto = false;
         this.thumbnailData = false;
         this.uploadComplete = false;
      },
      onUpload() {
         const data = new FormData();
         data.append('video', this.recordingData);
         data.append('thumbnail', this.thumbnailData);
         axios.post('/upload', data, {
            headers: {'Content-Type': `multipart/form-data; boundary=${data._boundary}`},
            onUploadProgress: progressEvent => this.updateProgress(Math.round( (progressEvent.loaded * 100) / progressEvent.total ))
         })
            .then(res => {
               this.uploadComplete = true;
            }).catch(err => {
               console.log(err);
            });
      },
      updateProgress(percentage) {
         this.progress = percentage;
      },
      onVideoInputChange(evt) {
         this.recordingData = evt.target.files[0];
         this.recordingMimeType = evt.target.files[0].type;
      },
      onPhotoInputChange(evt) {
         this.thumbnailData = evt.target.files[0];
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
</style>
