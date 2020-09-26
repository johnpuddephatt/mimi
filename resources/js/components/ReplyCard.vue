<template>
  <transition name="fade-out">
    <div v-if="!isDeleted">
      <div class="admin-reply" v-if="admin_user">
        <b-tooltip v-if="response_playlist" label="You’ve replied to this" type="is-dark" animated position="is-left" :delay="1000" class="admin-check-button--tooltip">
          <b-icon class="admin-check-button" type="is-light" icon="check"/>
        </b-tooltip>
        <create-reply v-else :reply_id="reply_id" :lesson_id="lesson_id" :user="admin_user"></create-reply>
      </div>

      <div class="card reply-card">
        <div class="card-image" @click="is_open = true">
          <figure class="image is-square">
            <img :src="thumbnail" alt="">
          </figure>
        </div>
        <div class="card-content is-justify-between">
          <div class="comment-author">
            <figure class="image is-48x48">
              <img class="is-rounded" :src="user.photo" />
            </figure>
            <p>
              <span class="is-size-6">{{ user.first_name }}</span>
              <span class="is-size-7"><timeago :datetime="time" :auto-update="60"></timeago></span>
            </p>
          </div>
          <b-button type="is-danger" outlined
             icon-right="delete" @click="confirmDelete"/>
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
  </transition>
</template>

<script>
export default {
  props: ['thumbnail', 'video', 'user', 'active_user', 'time', 'response_playlist', 'response_thumbnail', 'auto_open', 'reply_id', 'lesson_id', 'admin_user', 'show_feedback'],
  data() {
    return {
      is_open: false,
      currentSlide: 0,
      isDeleted: false
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
    },

    confirmDelete() {
      this.$buefy.dialog.confirm({
          title: 'Deleting reply',
          message: 'Are you sure you want to <b>delete</b> this reply? This action cannot be undone.',
          confirmText: 'Delete reply',
          type: 'is-danger',
          hasIcon: true,
          onConfirm: () => this.delete()
      })
    },

    delete() {
      axios({
          method: 'delete',
          url: `/lesson/${this.lesson_id}/reply/${this.reply_id}/delete`,
          timeout: 15000
        })
        .then(response => {
          this.$buefy.snackbar.open({
                  message: 'Reply deleted!',
                  position: 'is-bottom',
                  duration: 5000
          });
          this.isDeleted = true;
        })
        .catch(error => {
          this.$buefy.snackbar.open({
                  message: `<b>Error:</b> ${error.response.data.message}`,
                  type: 'is-danger',
                  position: 'is-bottom',
                  duration: 5000
                });
          axios.post('/log', {'error': `COMMENT GET ERROR\n${ platform.description }\n${ JSON.stringify(error) }`});
        })
    }
  }
};
</script>

<style>

</style>
