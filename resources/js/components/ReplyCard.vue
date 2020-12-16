<template>
  <transition name="fade-out">
    <div v-if="!isDeleted">

      <div class="admin-reply" v-if="active_user.is_admin">
        <b-tooltip v-if="feedback_playlist && !feedbackIsDeleted" label="You’ve replied to this" type="is-dark" animated position="is-left" :delay="1000" class="admin-check-button--tooltip">
          <b-icon class="admin-check-button" type="is-light" icon="check"/>
        </b-tooltip>
        <create-reply v-else :reply_id="reply_id" :lesson_id="lesson_id" :user="active_user" :should_open="open_reply_modal"></create-reply>
      </div>

      <div class="card reply-card">
        <div class="card-image" @click="is_open = true">
          <figure class="image is-square">
            <img :src="thumbnail" alt="">
          </figure>
        </div>
        <div class="card-content is-justify-between">
          <div class="reply-author">
            <figure class="image is-48x48">
              <img class="is-rounded" :src="user.photo" />
            </figure>
            <p>
              <span class="is-size-6">{{ user.first_name }}</span>
              <span class="is-size-7"><timeago :datetime="time" :auto-update="60"></timeago></span>
            </p>
          </div>
          <span v-if="comments_count > 0" class="tag is-rounded">{{ comments_count }} comments</span>
        </div>
      </div>

      <b-modal custom-class="has-background-white-bis reply-card-modal" :active.sync="is_open" has-modal-card trap-focus :destroy-on-hide="true" animation="zoom-in" aria-role="dialog" width="840px" aria-modal>

        <div class="carousel-wrapper column is-two-thirds is-paddingless">
          <div v-if="feedback_playlist && !feedbackIsDeleted" class="feedback-toggle field has-addons">
            <p class="control">
              <button class="button is-small" :class="currentSlide == 0 ? 'is-selected is-success' : ''" @click.prevent="currentSlide = 0">Student response</button>
            </p>
            <p class="control">
              <button class="button is-small" :class="currentSlide == 1 ? 'is-selected is-success' : ''" @click.prevent="currentSlide = 1">Teacher feedback</button>
            </p>
          </div>
          <transition name="fade-up">
            <b-button type="is-primary" icon-right="play" v-if="video_stopped && feedback_playlist && !feedbackIsDeleted && (currentSlide == 0)" @click.prevent="currentSlide = 1" size="is-medium" class="feedback-button">
              Watch teacher feedback
            </b-button>
          </transition>

          <b-carousel animated="fade" @change="updateSlide($event)" :arrow="false" :indicator="false" :has-drag="false" v-model="currentSlide" :autoplay="false" icon-size="is-medium">
           <b-carousel-item key="reply">
             <video-player @playing="video_stopped = null" @stopped="video_stopped = 'reply'" :should_autoplay="currentSlide == 0" :source="video" :poster="thumbnail" type="application/x-mpegURL"></video-player>
           </b-carousel-item>
           <b-carousel-item v-if="feedback_playlist && !feedbackIsDeleted" key="feedback">
             <video-player @playing="video_stopped = null" @stopped="video_stopped = 'feedback'" :should_autoplay="currentSlide == 1" :source="feedback_playlist" :poster="feedback_thumbnail" type="application/x-mpegURL"></video-player>
           </b-carousel-item>
          </b-carousel>
        </div>

        <div class="modal-card">
          <header class="modal-card-head is-radiusless reply-author">
            <figure class="image is-48x48">
              <img class="is-rounded" :src="user.photo" />
            </figure>
            <p>
              <span class="is-size-6">{{ user.first_name }}</span>
              <span class="is-size-7"><timeago :datetime="time" :auto-update="60"></timeago></span>
            </p>

            <b-dropdown v-if="active_user.id == user.id || active_user.is_admin" position="is-bottom-left" aria-role="list">
              <button class="button is-light" slot="trigger" slot-scope="{ active }">
                <b-icon icon="cog"></b-icon>
              </button>
              <b-dropdown-item @click="confirmDelete(reply_id)" aria-role="listitem">Delete</b-dropdown-item>
              <b-dropdown-item v-if="active_user.is_admin && feedback_id" @click="confirmDelete(feedback_id)" aria-role="listitem">Delete feedback</b-dropdown-item>
              <b-dropdown-item v-if="active_user.is_admin && !feedback_id" @click="openReplyModal" aria-role="listitem">Add feedback</b-dropdown-item>
            </b-dropdown>
            <button class="button reply-card-modal__close is-light" @click="is_open = false">
              <b-icon icon="close"></b-icon>
            </button>
          </header>
          <comments :user="active_user" :parent_user_name="user.first_name" :reply_id="reply_id" :lesson_id="lesson_id"></comments>
        </div>
      </b-modal>
    </div>
  </transition>
</template>

<script>
export default {
  props: ['thumbnail', 'video', 'user', 'active_user', 'is_admin', 'time', 'feedback_id', 'feedback_playlist', 'feedback_thumbnail', 'auto_open', 'reply_id', 'lesson_id', 'show_feedback', 'comments_count'],
  data() {
    return {
      is_open: false,
      currentSlide: null,
      isDeleted: false,
      feedbackIsDeleted: false,
      video_stopped: null,
      open_reply_modal: false
    }
  },

  watch: {
    is_open: function(value) {
      if(value) {
        if(!window.location.href.includes(`/reply/${this.reply_id}`)) {
          history.pushState(null, null, `${window.location.href}/reply/${this.reply_id}`);
        }
        this.$nextTick(() => {
          this.$root.$refs.instructionVideo.should_stop = true;
        });

        this.currentSlide = this.show_feedback ? 1 : 0;
      }
      else {
        history.pushState(null, null, window.location.href.replace('/feedback','').replace(`/reply/${this.reply_id}`, ''));
      }
    }
  },

  mounted() {
    if(this.auto_open) {
      this.is_open = true;
    }
    if(this.is_admin) {
      this.active_user.is_admin = true;
    }
  },

  methods: {
    onSubmit() {
    },

    openReplyModal() {
      this.is_open = false;
      this.open_reply_modal = true;
    },

    updateProgress(progress) {
    },

    updateSlide(value) {
      this.video_stopped = null;
      this.currentSlide = value;
    },

    confirmDelete(id) {
      this.$buefy.dialog.confirm({
          title: 'Confirm deletion',
          message: `Are you sure you want to <b>delete</b> this ${ (id == this.reply_id) ? 'student response' : 'teacher feedback'}? This action cannot be undone.`,
          confirmText: 'Delete',
          type: 'is-danger',
          hasIcon: true,
          onConfirm: () => this.delete(id)
      })
    },

    delete(id) {
      axios({
          method: 'delete',
          url: `/lesson/${this.lesson_id}/reply/${id}/delete`,
          timeout: 15000
        })
        .then(feedback => {
          this.$buefy.snackbar.open({
            message: 'Reply deleted!',
            position: 'is-bottom',
            duration: 5000
          });
          if(id == this.reply_id) {
            this.isDeleted = true;
          }
          else {
            this.updateSlide(0);
            this.feedbackIsDeleted = true;
          }
        })
        .catch(error => {
          this.$buefy.snackbar.open({
            message: `<b>Error:</b> ${error.feedback.data.message}`,
            type: 'is-danger',
            position: 'is-bottom',
            duration: 5000
          });
          axios.post('/log', {'error': `REPLY DELETE ERROR\n${ platform.description }\n${ JSON.stringify(error) }`});
        })
    }
  }
};
</script>

<style lang="scss">
@import "../../sass/variables";

.reply-card {
  border-radius: $radius;
  overflow: hidden;
  .card-image {
    position: relative;
    background-color: $grey-lighter;
    &::before {
      content: "\F101";
      font-size: 2.5em;
      background-color: transparentize($turquoise,0.3);
      color: white;
      height: 1.5em;
      width: 1.5em;
      line-height: 1.5em;
      text-align: center;
      border-radius: 9999px;
      z-index: 9;
      font-family: 'VideoJS';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
      transition: background-color 1s;
    }
    &:hover {
      &::before {
        background-color: transparentize($turquoise,0);
      }
    }
    img {
      object-fit: cover;
    }
  }
  &__loading {
    .card-image::before {
      content: none;
    }
  }
  .card-content {
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 1rem 1.5rem;
  }
  &__add {
    min-height: 22.5em;
    background-color: transparent;
    box-shadow: none;
    border: 2px dashed $grey-lighter;
    height: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 2em;
    justify-content: center;
  }
}

.reply-card-modal {
  .animation-content {
    position: relative;
    border-radius: $radius-large;
    overflow: hidden;
    width: 100%;
    max-width: 175vh;
    display: flex;

    @media screen and (orientation: portrait) {
      flex-direction: column;
      margin: 0;
      border-radius: 0;
      // min-height: 100vh;
      margin-top: auto;
      background-color: white;
      .modal-card {
        flex-grow: 1;
        min-height: calc(100vh - 100vw);
      }
    }
  }
  &__close {
    margin-left: 0.25rem;
  }
}

.admin-reply-button--tooltip, .admin-check-button--tooltip {
  position: absolute;
  z-index: 9;
  top: 1.25em;
  right: 1.25em;
}

.admin-reply-button, .admin-check-button {
  border-radius: 9999px;
}

.feedback-toggle,
.feedback-button {
  position: absolute;
  z-index: 9;
  left: 50%;
  bottom: 0.75em;
  transform: translateX(-50%);
  @media screen and (orientation: landscape) {
    bottom: 1.5em;
  }
}

.feedback-toggle {
  bottom: auto;
  top: 0.75em;
  width: auto !important;
  @media screen and (orientation: landscape) {
    top: 1.5em;
  }
}

.reply-author {
  display: inline-flex;
  align-items: center;
  flex-direction: row;
  border-bottom: none;

  p {
    margin-top: auto;
    margin-bottom: auto;
    padding-right: 1em;
    line-height: 1.2;
    margin-right: auto;

    span {
      display: block;
    }
  }

  figure {
    margin-right: .5em;
  }

  .tag {
    margin-left: auto;
  }
}

.carousel-wrapper {
  position: relative;
}

</style>
