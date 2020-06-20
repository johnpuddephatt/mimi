<template>
  <div>
    <div v-if="admin_user">
      <b-tooltip v-if="response_playlist" label="You’ve replied to this" type="is-dark" animated position="is-left" :delay="1000" class="admin-check-button--tooltip">
        <b-icon class="admin-check-button" type="is-light" icon="check"/>
      </b-tooltip>
      <create-reply v-else type="video" :reply_id="reply_id" :lesson_id="lesson_id" :user="admin_user"></create-reply>
    </div>

    <div class="card reply-card">
      <div class="card-image" @click="is_open = true">
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

    <b-modal custom-class=" has-background-white-bis reply-modal" :active.sync="is_open" has-modal-card trap-focus :destroy-on-hide="true" animation="zoom-in" aria-role="dialog" width="840px" aria-modal>
      <b-carousel @change="updateSlide($event)" :arrow="response_playlist ? true : false" :indicator="response_playlist ? true : false" :has-drag="false" v-model="currentSlide" class="column is-two-thirds is-paddingless" :autoplay="false" icon-size="is-medium">
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
        <b-button class="is-radiusless" expanded v-if="response_playlist" @click.prevent="currentSlide = (currentSlide == 0 ? 1 : 0)" v-html="currentSlide == 0 ? 'Show feedback' : 'Show original'"></b-button>
        <create-comment :user="active_user" :parent_user_name="user.first_name" :reply_id="reply_id" :lesson_id="lesson_id"></create-comment>
      </div>
    </b-modal>
  </div>
</template>

<script>
export default {
  props: ['thumbnail', 'video', 'user', 'active_user', 'time', 'response_playlist', 'response_thumbnail', 'auto_open', 'reply_id', 'lesson_id', 'admin_user', 'show_feedback'],
  data() {
    return {
      is_open: false,
      currentSlide: 0
    }
  },

  watch: {
    is_open: function() {
      if(this.is_open) {
        this.$root.$refs.instructionVideo.pause();
      }
    }
  },

  mounted() {

    if(this.auto_open) {
      this.is_open = true;
    }

    if(this.show_feedback) {
      this.updateSlide(1);
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
