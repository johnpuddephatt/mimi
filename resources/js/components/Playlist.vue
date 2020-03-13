<template>
   <transition-group name="fade" tag="div" class="video-wrapper">
      <video-player class="has-square-media" v-for="video in videos" :key="video.id" :source="video.playlist" :poster="video.thumbnail" type="application/x-mpegURL"></video-player>
   </transition-group>
   <!-- <ul>
      <li v-for="video in videos" :key="video.id">{{video.id}}</li>
   </ul> -->
</template>

<script>
export default {
   data() {
      return {
         videos: [],
         videoCount: null
      };
   },
   computed: {
   },

   watch: {
      videoCount: function(videoCount) {
         if(this.videos.length != videoCount) {
            this.fetchData();
         }
      },
   },

   created() {
      this.fetchData();
      setInterval(()=>{
         this.checkVideoTotal();
      }, 5000);
   },

   methods: {
      fetchData() {
         axios.get(`/` )
            .then(({data}) => this.videos = data);
      },
      checkVideoTotal() {
         axios.get(`/count` )
            .then(({data}) => this.videoCount = data.video_count);

      }
   }
};
</script>

<style>
   .video-wrapper .has-square-media {
      border-radius: calc(0.25rem - 1px);
      overflow: hidden;
   }
   .fade-enter-active, .fade-leave-active {
     transition: opacity 1s;
   }
   .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
     opacity: 0;
   }
</style>
