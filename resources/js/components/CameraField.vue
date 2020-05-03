<template>

<div class="camera-wrapper">
  <div class="notification is-danger" v-if="errorMessage">{{errorMessage}}</div>

  <div v-if="photoData" class="has-square-media">
    <img :src="photoUrl" />
  </div>

  <div v-else-if="videoData" class="card-body has-square-media">
    <video-player :source="videoUrl" :type="videoData.type"></video-player>
  </div>

  <div v-else class="has-square-media">
    <b-loading :is-full-page="false" :active="!isLoaded"></b-loading>
    <vue-web-cam ref="webcam" :device-id="deviceId" @started="onStarted" @error="onError" @cameras="onCameras" @camera-change="onCameraChange" @photo="onPhotoReady" width="640" height="480" />
  </div>

  <div class="camera-controls has-background-light is-bordered has-text-centered">

    <div v-if="photoData || videoData">
      <b-button size="is-medium" class="restart-camera" icon-right="camera-retake" @click.prevent="onRestart">Start again</b-button>
    </div>
    <div v-else>
      <b-tooltip v-show="!isRecording" label="Change camera" type="is-dark" animated position="is-bottom" :delay="1000" class="change-camera-tooltip">
        <b-button class="change-camera" icon-right="camera-switch" @click.prevent="nextCamera"/>
      </b-tooltip>

      <b-tooltip v-if="mode == 'video'" :label="isRecording ? 'Stop recording' : 'Start recording'" type="is-dark" animated position="is-bottom" :delay="1000" class="shutter-tooltip">
        <b-button size="is-large" type="is-danger" @click.prevent="onRecordToggle" class="take-photo" icon-right="radiobox-blank" />
      </b-tooltip>

      <b-tooltip v-else label="Take photo" type="is-dark" animated position="is-bottom" :delay="1000" class="shutter-tooltip">
        <b-button size="is-large" type="is-danger" @click.prevent="onCapture" class="take-photo" icon-right="camera-iris" />
      </b-tooltip>
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
  props: {
    mode: {
      default: 'photo',
      type: String
    }
  },
  data() {
    return {
      deviceId: null,
      devices: [],
      stream: null,
      cameraType: 'mediaRecorder',
      isStarted: false,
      isLoaded: false,
      isSetupVideo: false,
      photoData: null,
      videoData: null,
      mediaRecorder: null,
      isRecording: false,
      shouldStopRecording: false,
      errorMessage: null,
    };
  },
  computed: {
    device: function() {
      return this.devices.find(n => n.deviceId === this.deviceId);
    },
    mimeType: function() {
      if (this.recordingMimeType.startsWith('video/x-matroska')) {
        return 'video/mp4';
      } else {
        return this.recordingMimeType;
      }
    },
    photoUrl: function() {
      if (this.photoData) {
        return URL.createObjectURL(this.photoData);
      }
    },
    videoUrl: function() {
      if (this.videoData) {
        return URL.createObjectURL(this.videoData);
      }
    },
  },
  watch: {
    devices: function(devices) {
      // Once we have a list select the first one
      if (devices.length) {
        const [first, ...tail] = this.devices;
        if (this.devices.indexOf(localStorage.getItem('deviceId'))) {
          this.deviceId = localStorage.getItem('deviceId');
        }
        if (first && !this.deviceId) {
          this.deviceId = first.deviceId;
        }
      }
    },

  },
  created() {},
  methods: {

    onCapture() {
      this.$refs.webcam.capture();
    },

    onPhotoReady(blob) {
      this.photoData = blob;
      this.$emit("photo", this.photoData);
    },

    onVideoReady(blob) {
      this.videoData = blob;
      this.$emit("video", this.videoData);
    },

    onStarted(stream) {
      this.stream = stream;
      this.isStarted = true;

      setTimeout(() => {
        this.isLoaded = true;
      }, 1000)
    },
    onError(error) {
      this.errorMessage = error;
    },
    onRecordToggle() {
      if (!this.isSetupVideo) {
        this.setupVideo();
      }
      if (this.isRecording) {
        this.shouldStopRecording = true;
        // Timeout fallback for Safari, which only fires the dataavailable event once on stop
        setTimeout(() => {
          if (this.mediaRecorder.state == 'recording') {
            this.mediaRecorder.stop();
          }
        }, 1000);
      } else {
        this.isRecording = true;
        this.shouldStopRecording = false;
        this.mediaRecorder.start(1000);
      }
    },

    onRestart() {
      this.videoData = null;
      this.photoData = null;
    },

    onVideoInputChange(evt) {
      this.videoData = evt.target.files[0];
      // this.recordingMimeType = evt.target.files[0].type;
    },

    onCameras(cameras) {
      this.devices = cameras;
      // this.$refs.webcam.start();
    },
    onCameraChange(deviceId) {
      this.deviceId = deviceId;
    },

    nextCamera() {
      let currentDeviceIndex = this.devices.findIndex(n => n.deviceId === this.deviceId);
      let newDeviceIndex = (currentDeviceIndex + 1 == this.devices.length) ? 0 : (currentDeviceIndex + 1);
      let newDevice = this.devices[newDeviceIndex];
      this.deviceId = newDevice.deviceId;
    },

    setupVideo() {

      const options = {
        mimeType: 'video/webm'
      };

      const recordedChunks = [];

      this.mediaRecorder = new MediaRecorder(this.stream, options);
      this.recordingMimeType = this.mediaRecorder.mimeType;

      this.mediaRecorder.addEventListener('dataavailable', (e) => {
        if (e.data.size > 0) {
          recordedChunks.push(e.data);
        };
        if (this.shouldStopRecording && this.isRecording && this.mediaRecorder.state == 'recording') {
          this.mediaRecorder.stop();
        }
      });

      this.mediaRecorder.addEventListener('stop', () => {;
        this.isRecording = false;
        let blob = new Blob(recordedChunks, {
          type: this.recordingMimeType
        });
        this.onVideoReady(blob);
      });

      this.isSetupVideo = true;
    }
  }
};
</script>


<style>
.camera-wrapper {}

.camera-wrapper .has-square-media {
  border-radius: 5px 5px 0 0;
  border: 1px solid #efefef;
  border-bottom: none;
}

.camera-controls {
  padding: .5em 0;
  border-radius: 0 0 5px 5px;
}

.camera-controls button {
  border-radius: 9999px;
}

.camera-controls>* {
  vertical-align: middle;
}

.change-camera-tooltip {
  margin-left: -3rem;
  margin-right: .3rem;
}

.retake-photo {
  position: absolute;
  top: 1em;
  right: 1em;
}
</style>
