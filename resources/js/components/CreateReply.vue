<template>

  <div v-if="!isSaved" :class="reply_id ? '' : 'column is-full is-half-tablet is-one-third-widescreen is-one-quarter-fullhd'">

    <b-tooltip v-if="reply_id" label="Record admin reply" type="is-dark" animated position="is-left" :delay="1000" class="admin-reply-button--tooltip">
      <b-button class="admin-reply-button" @click="isReplyModalActive = true" size="is-light" icon-right="reply"/>
    </b-tooltip>

    <div v-else class="card reply-card reply-card__add">
      <b-button class="reply-button" @click="isReplyModalActive = true" expanded size="is-medium is-primary" icon-right="plus-circle">
        Add your reply
      </b-button>
    </div>

    <b-modal custom-class="create-reply-modal" :active.sync="isReplyModalActive" has-modal-card trap-focus :can-cancel="!reply.video && !isRecording" :destroy-on-hide="false" aria-role="dialog" width="420px" aria-modal>
      <div v-if="isSaving" class="modal-card is-rounded">
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
          <camera-field @is-recording="isRecording = $event" v-model="reply.video" mode="video"></camera-field>
        </section>
        <footer class="modal-card-foot has-background-light">
          <b-button :disabled="!reply.video" expanded type="is-primary" size="is-medium" @click.prevent="onSubmit" :loading="isSaving">Upload your reply</b-button>
        </footer>
      </div>
    </b-modal>
  </div>

  <b-tooltip v-else-if="reply_id" label="You’ve replied to this" type="is-dark" animated position="is-bottom" :delay="1000" class="admin-check-button--tooltip">
    <b-icon class="admin-check-button" type="is-light" icon="check"/>
  </b-tooltip>

  <div v-else class="column is-full is-half-tablet is-one-third-widescreen is-one-quarter-fullhd">

    <transition v-if="newVideo.playlist" name="fade">
      <reply-card
        :user="user"
        :thumbnail="newVideo.thumbnail"
        :video="newVideo.playlist"
        :time="newVideo.converted_for_streaming_at"
        :active_user="user"
        :reply_id="reply.id"
        :lesson_id="reply.lesson_id">
      </reply-card>
    </transition>

    <div v-else class="card reply-card reply-card__loading">
      <div class="card-image">
        <figure class="image is-square">
          <b-loading></b-loading>
        </figure>
      </div>
      <div class="card-content">
        <div class="reply-author">
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
import NoSleep from 'nosleep.js';
var platform = require('platform');

export default {
  props: ['user', 'lesson_id', 'reply_id', 'should_open'],
  data() {
    return {
      isReplyModalActive: false,
      isSaving: false,
      isRecording: false,
      isSaved: false,
      uploadProgress: null,
      newVideo: {},
      errors: '',
      noSleep: null,
      reply: {
        id: null,
        video: null,
        lesson_id: this.lesson_id,
        reply_id: this.reply_id,
        user_id: this.user.id,
        video_id: null,
      }
    }
  },

  watch: {
    isReplyModalActive: function() {
      this.$root.$refs.instructionVideo.pause();
    },
    should_open: function(should_open){
      if(should_open) {
        this.isReplyModalActive = true;
      }
    }
  },

  methods: {

    onSubmit() {
      this.isSaving = true;
      var noSleep = new NoSleep();
      noSleep.enable();
      const data = new FormData();

      for (let [key, value] of Object.entries(this.reply)) {
        if (value) {
          data.append(key, value);
        }
      }

      axios({
          method: 'post',
          url: `/lesson/${this.reply.lesson_id}/reply`,
          data: data,
          headers: {
            'Content-Type': `multipart/form-data; boundary=${data._boundary}`
          },
          onUploadProgress: progressEvent => this.updateProgress(Math.round((progressEvent.loaded * 100) / progressEvent.total)),
          timeout: 90000
        })
        .then(response => {

          this.reply = response.data;
          noSleep.disable();
          this.isSaved = true;
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
        })
        .catch(error => {
          noSleep.disable();
          this.isSaving = false;
          this.$buefy.snackbar.open({
            message: `<b>Error:</b> ${error.response.data.message}`,
            type: 'is-danger',
            position: 'is-bottom',
            duration: 5000
          });
          axios.post('/log', {'error': `\nREPLY FIELD ERROR\n${ platform.description }\nError: ${ error.name }: ${ error.message }\nReply data: ${JSON.stringify(this.reply)}\n\n`});
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

<style lang="scss">
  .create-reply-modal {
    .modal-card-foot {
      padding-top: 10px;
      padding-bottom: 10px;
    }
    .field {
      width: 100%;
    }
  }
</style>
