<template>
  <div>
    <b-button class="reply-button" v-if="!isSaved" @click="isReplyModalActive = true" expanded size="is-medium is-primary" icon-right="plus-circle">
      Add your reply
    </b-button>

    <h3 class="is-size-4 replies-heading">Replies</h3>
    <div v-if="isSaved">

        <transition v-if="newVideoPlaylist" name="fade">
          <div class="card reply-card">
            <div class="column is-paddingless is-6-widescreen">
              <video-player :source="newVideoPlaylist" type="application/x-mpegURL"></video-player>
            </div>
            <div class="column is-6-widescreen">

            </div>
          </div>
        </transition>
        <b-notification v-else type="is-warning" role="alert">
          Your video is being processed.
        </b-notification>

    </div>

    <b-modal :active.sync="isReplyModalActive" has-modal-card trap-focus :can-cancel="!reply.video" :destroy-on-hide="false" aria-role="dialog" width="420px" aria-modal>
      <div v-if="isSaving" class="modal-card">
        <section class="modal-card-body has-text-centered">
          <div v-if="isSaved">
            <h3 class="title">Ottimo <span class="emoji">✨</span></h3>
            <p class="subtitle">Your reply has been saved</p>
          </div>
          <div v-else>
            <h3 class="title">Un momento <span class="emoji">⌛</span></h3>
            <p class="subtitle">Your reply is uploading</p>
          </div>
          <div class="circle-loader" :class="isSaved? 'load-complete' : ''">
            <div v-if="isSaved" class="checkmark draw"></div>
          </div>
          <div v-if="isSaved">
            <b-button @click.prevent="onModalEnd">Close</b-button>
          </div>
          <div v-else>
            {{ uploadProgress }}% uploaded
          </div>
        </section>
      </div>

      <div v-else class="modal-card">
        <!-- <header class="modal-card-head">
          <p class="modal-card-title">Create your reply</p>
        </header> -->
        <section class="modal-card-body is-paddingless" style="overflow: visible;">
          <camera-field v-model="reply.video" mode="video"></camera-field>
        </section>
        <footer class="modal-card-foot is-paddingless">
          <b-button :disabled="!reply.video" expanded type="is-primary" size="is-medium" @click.prevent="onSubmit" :loading="isSaving">Upload your reply</b-button>
        </footer>
      </div>
    </b-modal>
  </div>
</template>

<script>
var platform = require('platform');

export default {
  props: ['user_id', 'lesson_id', 'parent_id'],
  data() {
    return {
      isReplyModalActive: false,
      isSaving: false,
      isSaved: false,
      uploadProgress: null,
      newVideoPlaylist: null,
      errors: '',
      reply: {
        id: null,
        type: null,
        video: null,
        lesson_id: this.lesson_id,
        parent_id: this.parent_id,
        user_id: this.user_id,
        video_id: null,
      }
    }
  },

  methods: {


    onSubmit() {
      this.isSaving = true;

      const data = new FormData();

      for (let [key, value] of Object.entries(this.reply)) {
        if (value) {
          data.append(key, value);
        }
      }

      // data.append('_method', 'POST');
      axios({
          method: 'post',
          url: `/admin/comment/create`,
          data: data,
          headers: {
            'Content-Type': `multipart/form-data; boundary=${data._boundary}`
          },
          onUploadProgress: progressEvent => this.updateProgress(Math.round((progressEvent.loaded * 100) / progressEvent.total)),
          timeout: 30000
        })
        .then(response => {

          this.reply = response.data;
          axios.post('/log', {'reply field axios response': `${ platform.description }\n${ response.data }`});

          var videoReadyCheck = setInterval(
            () => {
              axios({
                  method: 'get',
                  url: `/admin/video/${this.reply.video_id}`
                })
                .then(response => {
                  if(response.data.converted_for_streaming_at) {
                    this.newVideoPlaylist = response.data.playlist;
                    clearInterval(videoReadyCheck);
                  }
                })
                .catch(error => {
                })
            }, 5000
          );
          setTimeout(
            () => {
              this.isSaved = true;
            }, 1000
          )
        })
        .catch(error => {
          this.isSaving = false;
          this.$buefy.snackbar.open({
            message: `<strong>Error:</strong> ${error.response.data.message}`,
            type: 'is-danger',
            position: 'is-bottom',
            duration: 5000
          });
          axios.post('/log', {'reply field axios error': `${ platform.description }\n${ error.response.data.message }`});
          this.errors = error.response.data.errors || '';
        });
    },

    updateProgress(progress) {
      this.uploadProgress = progress;
    },

    onModalEnd() {
      this.isReplyModalActive = false;
      this.isSaving = false;
      this.uploadProgress = null;
      this.errors = null;
      this.reply.video = null;
    }
  }
};
</script>


<style>
.modal-card {
  max-width: 100%;
}

.reply-button {
  max-width: 480px;
  margin: 1.5em auto;
}

.replies-heading {
  margin-bottom: 1rem;
}
</style>
