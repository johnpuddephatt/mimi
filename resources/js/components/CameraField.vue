<template>

<div class="camera-wrapper">

  <!-- Camera preview -->

  <div v-if="value">
    <div v-if="mode == 'photo'" class="has-square-media">
      <img :src="dataUrl" />
    </div>

    <div v-else class="card-body">
      <video-player :source="dataUrl" :type="mimeType || value.type ||  'application/x-mpegURL'"></video-player>
    </div>

    <div class="camera-controls has-background-light is-bordered has-text-centered">
        <b-button class="restart-camera" icon-right="camera-retake" @click.prevent="onRestart">Start again</b-button>
    </div>
  </div>

  <!-- Camera capture -->
  <div v-else>

    <section v-if="cameraType == 'fallback'" class="section has-background-light has-text-centered">

      <!-- <b-notification
        type="is-info"
        has-icon
        role="alert"
        v-if="warningMessage"
        closable="false"
        :message="warningMessage">
      </b-notification> -->

      <div class="reply-card__add">
        <b-upload @input="onFileInputChange" type="file" name="file" :accept="accept[mode]" capture="user">
          <a class="button">
            <b-icon icon="camera"></b-icon>
            <span>Add your {{ mode }}</span>
          </a>
        </b-upload>
      </div>
    </section>

    <div v-else>
      <b-loading :is-full-page="false" :active="!isLoaded"></b-loading>
      <div class="has-square-media">
        <span v-if="isRecording"  class="recording-indicator tag is-black">Rec</span>
        <vue-web-cam ref="webcam" :device-id="deviceId" @started="onStarted" @error="onError" @cameras="onCameras" @camera-change="onCameraChange" @photo="onData" width="640" height="480" />
      </div>
      <div class="camera-controls has-background-light is-bordered has-text-centered">
        <b-tooltip v-if="devices.length > 1" v-show="!isRecording" label="Change camera" type="is-dark" animated position="is-bottom" :delay="1000" class="change-camera-tooltip">
          <b-button size="is-medium" class="change-camera" icon-right="camera-switch" @click.prevent="nextCamera"/>
        </b-tooltip>

        <b-tooltip v-if="mode == 'video'" :label="isRecording ? 'Stop recording' : 'Start recording'" type="is-dark" animated position="is-bottom" :delay="1000" class="shutter-tooltip">
          <b-button size="is-large" type="is-danger" @click.prevent="onRecordToggle" class="take-photo" icon-right="radiobox-blank" />
        </b-tooltip>

        <b-tooltip v-else label="Take photo" type="is-dark" animated position="is-bottom" :delay="1000" class="shutter-tooltip">
          <b-button size="is-large" type="is-danger" @click.prevent="onCapture" class="take-photo" icon-right="camera-iris"/>
        </b-tooltip>

        <b-tooltip label="Select a file from your device" type="is-dark" animated position="is-bottom" :delay="1000" class="file-upload-tooltip">
          <b-upload @input="onFileInputChange" size="is-medium" type="file" name="file" :accept="accept[mode]" capture="user">
            <b-button tag="a" size="is-medium" class="file-upload" icon-right="file-upload">
            </b-button>
          </b-upload>
        </b-tooltip>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import VueWebCam from "./WebCam";
var platform = require('platform');

export default {
  components: {
    'vue-web-cam': VueWebCam
  },
  props: {
    mode: {
      default: 'photo',
      type: String
    },
    value: {
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
      rawMimeType: null,
      newValue: null,
      mediaRecorder: null,
      isRecording: false,
      shouldStopRecording: false,
      errorMessage: null,
      warningMessage: null,
      accept: {
        photo: 'image/*',
        video: 'video/webm,video/x-matroska,video/mp4,video/x-m4v,video/*'
      }
    };
  },
  computed: {
    device: function() {
      return this.devices.find(n => n.deviceId === this.deviceId);
    },
    mimeType: function() {
      return (this.rawMimeType && (this.rawMimeType.startsWith('video/x-matroska') || this.rawMimeType.startsWith('video/quicktime'))) ? 'video/mp4' : this.rawMimeType;
    },
    dataUrl: function() {
      return this.value instanceof Blob ? URL.createObjectURL(this.value) : this.value;
    }
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

  created: function () {
    if(this.mode == 'video' && !window.MediaRecorder) {
      if(platform.product != 'iPad' && platform.product != 'iPhone') {
        this.warningMessage = "Your browser doesn’t support accessing your camera directly.";
      }
      this.cameraType = 'fallback';
    }
  },

  methods: {

    onCapture() {
      this.$refs.webcam.capture();
    },

    onData(blob) {
      this.isLoaded = false;
      this.isSetupVideo = false;
      this.rawMimeType = blob.type;
      this.$emit('input', blob)
    },

    onStarted(stream) {
      this.stream = stream;
      this.isStarted = true;

      setTimeout(() => {
        this.isLoaded = true;
      }, 1000)
    },

    onError(error) {
      this.cameraType = 'fallback';
      axios.post('/log', {'error': `CAMERAFIELD ERROR\n${ platform.description }\n${ error }`});

      if (error.name == "NotFoundError") {
        this.errorMessage = "<strong>Camera or microphone not found</strong><br>A suitable camera and/or microphone could not be found on your device. Please ensure your devices are connected and turned on, then refresh this page to try again.";
      } else if (error.name == "NotReadableError") {
          this.errorMessage = "<strong>Couldn’t access your camera or microphone</strong><br>Your camera and/or microphone can’t be reached. They may be in use by another website or application. Try closing any applications using your camera/microphone and ensure you’ve given this website permission to access your camera and/or microphone.";
      } else if (error.name == "OverconstrainedError") {
        this.errorMessage = "<strong>Camera or microphone not found</strong><br>A suitable camera and/or microphone could not be found on your device. Please ensure your devices are connected and turned on, then refresh this page. If the problem persists, you may need to check your system settings to make sure your browser has permission to access your camera.";
      } else if (error.name == "NotAllowedError") {
        this.errorMessage = `<strong>Permission denied</strong><br>Please give this website permission to access your camera and/or microphone, then refresh this page to try again.<br><br>• <a target='_blank' href='https://support.google.com/chrome/answer/2693767'>Enable access in Google Chrome</a><br>• <a target='_blank' href='https://support.mozilla.org/en-US/kb/how-manage-your-camera-and-microphone-permissions'>Enable access in Firefox</a><br>• <a target='_blank' href='https://support.apple.com/en-gb/guide/safari/ibrwe2159f50/mac'>Enable access in Safari</a><br><br>Alternatively, click ‘OK’ followed by ‘Add your ${ this.mode }’ to continue in file upload mode.`;
      } else if (error.name == "TypeError") {
        this.errorMessage = "Sorry, something went wrong. Please try again.";
      } else if(error.name == "NoGetUserMedia") {
        if(platform.product != 'iPad' || platform.product != 'iPhone') {
          this.warningMessage = "Your browser doesn’t support accessing your camera directly.";
        }
      } else {
        this.errorMessage = "Unknown error: " + error
      }

      if(this.errorMessage) {
        this.$buefy.dialog.alert({
          title: 'Error',
          message: this.errorMessage,
          type: 'is-danger',
          hasIcon: true,
          icon: 'alert',
          ariaRole: 'alertdialog',
          ariaModal: true
        })
      }
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
      this.$emit('input', null)
    },

    onFileInputChange(file) {
      this.rawMimeType = file.type || '';
      this.$emit('input', file);
    },

    onCameras(cameras) {
      this.devices = cameras;
      this.$refs.webcam.start();
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
        this.onData(blob);
      });

      this.isSetupVideo = true;
    }
  }
};
</script>


<style>
.camera-wrapper {
  position: relative;
}

.field .camera-wrapper .has-square-media {
  border-radius: 5px 5px 0 0;
  border: 1px solid #dbdbdb;
}

.camera-controls {
  padding: .5em 0;
}

.field .camera-controls {
  border-radius: 0 0 5px 5px;
  border: 1px solid #dbdbdb;
  border-top: none;
}

.camera-controls button,
.camera-controls a {
  border-radius: 9999px;
}

.camera-controls>* {
  vertical-align: middle;
}

.change-camera-tooltip {
  margin-left: -5rem;
  margin-right: 1.5rem;
}

.file-upload-tooltip {
  margin-left: 1.5rem;
  margin-right: -5rem;
}


.recording-indicator {
  position: absolute;
  right: .5em;
  top: .5em;
  z-index: 9;
  padding-right: 2em !important;
}

.recording-indicator::after {
  width: .75em;
  height: .75em;
  position: absolute;
  right: .75em;
  background-color: red;
  border-radius: 9999px;
  content: '';
  animation: pulse 1s infinite ease;
}

@keyframes pulse {
  0% {
    transform: scale(0.75);
  }
}
</style>
