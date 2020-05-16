<template>

  <div v-if="!isSaved" :class="comment_id ? '' : 'column is-full is-half-tablet is-one-third-widescreen is-one-quarter-fullhd'">

    <b-tooltip v-if="comment_id" label="Record admin reply" type="is-dark" animated position="is-left" :delay="1000" class="admin-reply-button--tooltip">
      <b-button class="admin-reply-button" @click="isReplyModalActive = true" size="is-light" icon-right="reply"/>
    </b-tooltip>

    <div v-else class="card reply-card reply-card__add">
      <b-button class="reply-button" @click="isReplyModalActive = true" expanded size="is-medium is-primary" icon-right="plus-circle">
        Add your reply
      </b-button>
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
        <section class="modal-card-body is-paddingless" style="overflow: visible;">
          <camera-field v-model="reply.video" mode="video"></camera-field>
        </section>
        <footer class="modal-card-foot has-background-light">
          <b-button :disabled="!reply.video" expanded type="is-primary" size="is-medium" @click.prevent="onSubmit" :loading="isSaving">Upload your reply</b-button>
        </footer>
      </div>
    </b-modal>
  </div>

  <b-tooltip v-else-if="comment_id" label="You’ve replied to this" type="is-dark" animated position="is-bottom" :delay="1000" class="admin-check-button--tooltip">
    <b-icon class="admin-check-button" type="is-light" icon="check"/>
  </b-tooltip>

  <div v-else class="column is-full is-half-tablet is-one-third-widescreen is-one-quarter-fullhd">

    <transition v-if="newVideo.playlist" name="fade">
      <reply-card :user="user" :thumbnail="newVideo.thumbnail" :video="newVideo.playlist" :time="newVideo.converted_for_streaming_at"></reply-card>
    </transition>

    <div v-else class="card reply-card reply-card__loading">

      <div class="card-image">
        <figure class="image is-square">
          <b-loading></b-loading>
        </figure>
      </div>
      <div class="card-content">
        <div class="comment-author">
          <figure class="image is-48x48">
            <img class="is-rounded" :src="user.photo" />
          </figure>
          <p>
            <span class="is-size-6">{{ user.first_name}}</span>
            <span class="is-size-7">Processing...</span>
          </p>
        </div>
      </div>
    </div>
  </div>


</template>

<script>
var platform = require('platform');

export default {
  props: ['user', 'lesson_id', 'comment_id', 'type'],
  data() {
    return {
      isReplyModalActive: false,
      isSaving: false,
      isSaved: false,
      uploadProgress: null,
      newVideo: {},
      errors: '',
      reply: {
        id: null,
        type: this.type,
        video: null,
        lesson_id: this.lesson_id,
        comment_id: this.comment_id,
        user_id: this.user.id,
        video_id: null,
      }
    }
  },

  watch: {
    isReplyModalActive: function() {
      this.$root.$refs.instructionVideo.pause();
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

      axios({
          method: 'post',
          url: `/lesson/${this.reply.lesson_id}/comment`,
          data: data,
          headers: {
            'Content-Type': `multipart/form-data; boundary=${data._boundary}`
          },
          onUploadProgress: progressEvent => this.updateProgress(Math.round((progressEvent.loaded * 100) / progressEvent.total)),
          timeout: 30000
        })
        .then(response => {

          this.reply = response.data;

          var videoReadyCheck = setInterval(
            () => {
              axios({
                  method: 'get',
                  url: `/lesson/${this.reply.lesson_id}/video/${this.reply.video_id}`
                })
                .then(response => {
                  if(response.data.converted_for_streaming_at) {
                    this.newVideo = response.data;
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
            message: `<b>Error:</b> ${error.response.data.message}`,
            type: 'is-danger',
            position: 'is-bottom',
            duration: 5000
          });
          axios.post('/log', {'error': `REPLY FIELD ERROR\n${ platform.description }\n${ error.response.data.message }`});
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

.admin-reply-button--tooltip, .admin-check-button--tooltip {
  position: absolute;
  top: 1.25em;
  right: 1.25em;
}
.admin-reply-button, .admin-check-button {
  border-radius: 9999px;
}

</style>
