<template>
  <div class="camera-wrapper">
    <div class="has-square-media">
      <vue-web-cam ref="webcam" @photo="onPhotoReady" :device-id="deviceId" @started="onStarted" @stopped="onStopped" @error="onError" @cameras="onCameras" @camera-change="onCameraChange" width="640" height="480" />
    </div>
    <b-loading :is-full-page="false" :active="!isLoaded"></b-loading>
    <div class="camera-controls">
      <b-tooltip label="Change camera" type="is-dark" animated position="is-bottom" :delay="1000" class="change-camera-tooltip">
        <b-button rounded class="change-camera" icon-right="camera-switch" @click.prevent="nextCamera" />
      </b-tooltip>
      <b-tooltip label="Take photo" type="is-dark" animated position="is-bottom" :delay="1000" class="take-photo-tooltip">
        <b-button size="is-large" rounded type="is-danger" @click.prevent="onCapture" class="take-photo" icon-right="camera-iris" />
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
  data() {
    return {
      deviceId: null,
      devices: [],
      cameraType: false,
      isLoaded: false,
    };
  },
  computed: {
    device: function() {
      return this.devices.find(n => n.deviceId === this.deviceId);
    }
  },
  watch: {
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
  },
  methods: {
    onCapture() {
      this.$refs.webcam.capture();
    },
    onPhotoReady(blob) {
      this.$emit("photo", blob);
    },
    onStarted(stream) {
      setTimeout(()=>{
        this.isLoaded = true;
      }, 1000)
    },
    onStopped(stream) {
      console.log("On Stopped Event", stream);
    },
    onError(error) {
      console.log("On Error Event", error);
    },
    onCameras(cameras) {
      this.devices = cameras;
    },
    onCameraChange(deviceId) {
      this.deviceId = deviceId;
    },
    nextCamera() {
      let currentDeviceIndex = this.devices.findIndex(n => n.deviceId === this.deviceId);
      let newDeviceIndex = (currentDeviceIndex + 1 == this.devices.length) ? 0 : (currentDeviceIndex + 1);
      let newDevice = this.devices[newDeviceIndex];
      this.deviceId = newDevice.deviceId;

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
  .camera-wrapper {
  }

  .camera-wrapper .has-square-media {
    border-radius: 5px 5px 0 0;
    border: 1px solid #efefef;
    border-bottom: none;
  }

  .camera-controls {
    padding: .5em 0;
    text-align: center;
    border-radius: 0 0 5px 5px;
    background-color: #f5f5f5;
    border: 1px solid #efefef;
  }

  .camera-controls > * {
    vertical-align: middle;
  }

  .change-camera-tooltip {
    margin-left: -4rem;
    margin-right: 1rem;
  }
</style>
