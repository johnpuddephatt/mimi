<template>
  <div>
    <div class="card reply-card">
      <div class="card-image" @click="isReplyCardModalActive = true">
        <figure class="image is-square">
          <img :src="thumbnail" alt="">
        </figure>
      </div>
      <div class="card-content">
        <div class="comment-author">
          <figure class="image is-48x48">
            <img class="is-rounded" :src="user.photo" />
          </figure>
          <p>
            <span class="is-size-6">{{ user.first_name }}</span>
            <span class="is-size-7"><timeago :datetime="time" :auto-update="60"></timeago></span>
          </p>
        </div>
      </div>
    </div>

    <b-modal custom-class=" has-background-white-bis reply-modal" :active.sync="isReplyCardModalActive" has-modal-card trap-focus :destroy-on-hide="true" animation="zoom-in" aria-role="dialog" width="840px" aria-modal>
      <b-carousel @change="updateSlide($event)" :arrow="response_playlist ? true : false" :indicator="response_playlist ? true : false" :has-drag="true" v-model="currentSlide" class="column is-two-thirds is-paddingless" :autoplay="false" icon-size="is-medium">
       <b-carousel-item :key="0">
         <video-player :play="currentSlide == 0 ? true : false" :autoplay="true" :source="video" :poster="thumbnail" type="application/x-mpegURL"></video-player>
       </b-carousel-item>

       <b-carousel-item v-if="response_playlist" :key="1">
         <video-player :play="currentSlide == 1 ? true : false" :autoplay="false" :source="response_playlist" :poster="response_thumbnail" type="application/x-mpegURL"></video-player>
       </b-carousel-item>
   </b-carousel>
      <div class="modal-card">
        <header class="modal-card-head is-radiusless comment-author">
          <figure class="image is-48x48">
            <img class="is-rounded" :src="user.photo" />
          </figure>
          <p>
            <span class="is-size-6">{{ user.first_name }}</span>
            <span class="is-size-7"><timeago :datetime="time" :auto-update="60"></timeago></span>
          </p>
        </header>
        <section class="modal-card-body">
          <b-button expanded v-if="response_playlist" @click.prevent="currentSlide = (currentSlide == 0 ? 1 : 0)" v-html="currentSlide == 0 ? 'Show feedback' : 'Show original'"></b-button>
        </section>
        <!-- <footer class="modal-card-foot is-radiusless">
          <div class="field is-grouped">
            <div class="control is-expanded">
              <input type="text" value="" name="comment" disabled class="input" placeholder="Enter your reply">
            </div>
            <div class="control">
              <button class="button" disabled>Add</button>
            </div>
          </div>
        </footer> -->
      </div>
    </b-modal>

  </div>
</template>

<script>
export default {
  props: ['thumbnail', 'video', 'user', 'time', 'response_playlist', 'response_thumbnail'],
  data() {
    return {
      currentSlide: 0,
      isReplyCardModalActive: false
    }
  },

  watch: {
    isReplyCardModalActive: function() {
      this.$root.$refs.instructionVideo.pause();
    }
  },

  methods: {
    onSubmit() {
    },

    updateProgress(progress) {
    },

    updateSlide(value) {
      this.currentSlide = value;
    }
  }
};
</script>

<style>

</style>
