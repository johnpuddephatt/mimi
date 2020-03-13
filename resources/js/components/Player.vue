<template>
   <div>
      <video
         class="video-js vjs-big-play-centered"
         controls
         preload="none"
         width="640"
         height="264"
         :poster="poster"
         playsinline
         ref="player"
       >
         <source :src="source" :type="type" />
         <p class="vjs-no-js">
           To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
         </p>
      </video>
   </div>
</template>

<script>
import videojs from "video.js";

export default {
   props: ['source', 'type', 'poster'],
   data() {
      return {
         player: null
      };
   },
   mounted() {
      this.player = videojs(this.$refs.player);
      // console.log(this.$refs.player);
   },
   beforeDestroy() {
      this.player.dispose();
   }
};
</script>


<style>
   @import '~video.js/dist/video-js.css';

   .vjs-poster {
      background-size: cover;
   }
</style>
