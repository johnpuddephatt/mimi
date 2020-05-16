<template>
<div class="video-player--wrapper">
  <div class="has-square-media">
    <b-loading :is-full-page="false" :active.sync="isLoading"></b-loading>
    <video class="video-js vjs-big-play-centered" controls preload="none" width="640" height="264" :autoplay="autoplay" :poster="poster" playsinline ref="player">
      <source :src="source" :type="type" />
      <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
      </p>
    </video>
  </div>
</div>
</template>

<script>
import videojs from "video.js";

export default {
  props: ['play', 'source', 'type', 'poster', 'autoplay'],
  data() {
    return {
      isLoading: true,
      player: null
    };
  },

  watch: {
    play: function(val) { // watch it
      if (val == false) {
        this.isLoading = false;
        this.player.pause();
      } else {
        this.isLoading = false;
        this.player.play();
      }

    }
  },

  mounted() {
    this.player = videojs(this.$refs.player, {
      // html5: {
      //   hls: {
      //     overrideNative: true
      //   }
      // }
    });
    this.player.ready(() => {

      this.isLoading = false;
      // console.log('player ready');
      //
      // this.player.on('ended', function() {
      //   console.log('player ended');
      // });

      this.player.on("play", () => {
        this.isLoading = false;

        if (this.player.currentTime() === 0) {
          this.player.currentTime(0);
        }
      });

    });

  },
  beforeDestroy() {
    this.player.dispose();
  },

  methods: {
    pause() {
      this.player.pause();
    }
  }
};
</script>


<style>
@import '~video.js/dist/video-js.css';

.video-player--wrapper {
  position: relative;
  flex-grow: 0;
}

.video-player--wrapper .has-square-media {
  border: none;
}

.vjs-poster {
  background-size: cover;
}

.video-js .vjs-big-play-button {
  height: 2em;
  width: 2em;
  margin-left: -1em;
  margin-top: -1em;
  border-radius: 9999px;
  background-color: #00C2CDaa;
  border: none;
}

.video-js:hover .vjs-big-play-button,
.video-js .vjs-big-play-button:focus {
  background-color: #00C2CDff;
}

.video-js .vjs-big-play-button .vjs-icon-placeholder::before {
  line-height: 1.333em;
  color: #fff;
  font-size: 1.5em;
}

.video-js.vjs-ended .vjs-big-play-button {
  display: block;
}

.video-js.vjs-ended .vjs-big-play-button .vjs-icon-placeholder::before {
  content: "\F116";
}

.video-js .vjs-control-bar {
  background-color: #00C2CDaa;
  bottom: 4em;
  left: 2.5em;
  right: 2.5em;
  width: auto;
  border-radius: 5px;
  padding-top: .5em;
  padding-bottom: .5em;
  height: 4em;
}

.vjs-has-started .vjs-control-bar {
  opacity: 0;
  transition: 400ms 750ms;
}

.vjs-has-started:hover .vjs-control-bar {
  opacity: 1;
  transition: 200ms 0ms;
}

.vjs-fullscreen video {
  object-fit: contain !important;
}
</style>
