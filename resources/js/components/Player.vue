<template>
   <video
      class="video-js vjs-big-play-centered"
      controls
      preload="none"
      width="640"
      height="264"
      :poster="poster"
      data-setup="{}"
      playsinline
    >
      <source :src="source" :type="type" />
      <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
      </p>
    </video>
</template>

<script>
import videojs from "video.js";

export default {
   props: ['source', 'type', 'poster'],
   data() {
      return {
      };
   },
};
</script>


<style>
   @import '~video.js/dist/video-js.css';

   div.video-js {
      max-width: 100%;
      margin: 2em 0;
      padding-top: 100%;
      position: relative;
   }
   div.video-js video {
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      max-width: 100%;
      display: block;
      height: 100%;
   }
</style>
