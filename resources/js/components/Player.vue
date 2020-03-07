<template>
   <video
      id="my-video"
      class="video-js"
      controls
      preload="auto"
      width="640"
      height="264"
      poster="https://www.placecage.com/480/360"
      data-setup="{}"
    >
      <source :src="source" type="application/x-mpegURL" />
      <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
      </p>
    </video>
</template>

<script>
import videojs from "video.js";

export default {
   props: ['source'],
   data() {
      return {
      };
   },
};
</script>


<style>
   @import '~video.js/dist/video-js.css';
</style>
