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
      <video-player class="column is-two-thirds is-paddingless" :autoplay="true" :source="video" type="application/x-mpegURL"></video-player>
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
          Comments...
        </section>
        <footer class="modal-card-foot is-radiusless">
          <div class="field is-grouped">
            <div class="control is-expanded">
              <input type="text" value="" name="email" class="input is-flat" placeholder="Enter your reply">
            </div>
            <div class="control">
              <button class="button">Add</button>
            </div>
          </div>
        </footer>
      </div>
    </b-modal>

  </div>
</template>

<script>
export default {
  props: ['thumbnail', 'video', 'user', 'time'],
  data() {
    return {
      isReplyCardModalActive: false
    }
  },

  methods: {
    onSubmit() {
    },

    updateProgress(progress) {
    }
  }
};
</script>

<style>

</style>
