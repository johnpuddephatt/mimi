<template>

<div class="comment-wrapper">


  <div class="comment-list modal-card-body" v-if="errorLoading">
    <div class="notification">
      An error occured while trying to load comments
    </div>
  </div>

  <transition-group ref="comments" name="list" tag="div" v-else-if="comments.length && isLoaded" class="comment-list modal-card-body">

    <div v-for="comment in comments" class="comment-wrapper" :key="comment.id">
      <div class="comment-card" @click="currentlyCommentingOn = comment.id">
        <figure class="image is-32x32">
          <img class="is-rounded" :src="comment.user.photo" />
        </figure>
        <div>
          <p class="is-size-7 has-text-weight-semibold">
            {{ comment.user.first_name }}
            <timeago class="has-text-grey has-text-weight-light" :datetime="comment.created_at" :auto-update="60"></timeago>
            <b-icon class="comment-card__reply-button" type="is-success" size="is-small" icon="reply"></b-icon>
          </p>
          <div class="comment-card__value is-size-7" v-html="comment.value"></div>
        </div>
      </div>
      <transition-group class="child-comments" :ref="`child-comments-${comment.id}`" name="list" tag="div">
        <div :key="child_comment.id" class="comment-card comment-card--child" v-for="child_comment in comment.comments"  @click="currentlyCommentingOn = comment.id">
          <figure class="image is-32x32">
            <img class="is-rounded" :src="child_comment.user.photo" />
          </figure>
          <div>
            <p class="is-size-7 has-text-weight-semibold">
              {{ child_comment.user.first_name }}
              <timeago class="has-text-grey has-text-weight-light" :datetime="child_comment.created_at" :auto-update="60"></timeago>
              <b-icon class="comment-card__reply-button" type="is-success" size="is-small" icon="reply"></b-icon>
            </p>
            <div class="comment-card__value is-size-7" v-html="child_comment.value"></div>
          </div>
        </div>
      </transition-group>

      <div class="pl-6 field is-grouped comment-reply-field" v-if="currentlyCommentingOn == comment.id">
        <div class="control is-expanded">
          <textarea
            rows="1"
            @input="onTextareaInput"
            :ref="`commentInput${ comment.id }`"
            maxlength="254"
            class="textarea"
            v-html="currentlyCommentingOn ? newCommentValue : ''"
            :placeholder="`Reply to ${ parent_user_name }`">
          </textarea>
          <b-button icon-left="send" aria-label="Send message" :loading="isSaving" @click.prevent="onSubmit" :disabled="!newCommentValue"></b-button>
        </div>
      </div>
    </div>

  </transition-group>

  <div v-else-if="isLoaded" class="comment-list comment-list__empty modal-card-body">
    <div class="has-text-centered notification is-white">
      Be first to comment!
    </div>
  </div>

  <div v-else class="comment-list modal-card-body">
    <b-loading :is-full-page="false" :active="!isLoaded"></b-loading>
  </div>

  <footer class="modal-card-foot comment-foot is-radiusless">
    <div class="comment-reply-field field is-grouped">
      <div v-show="currentlyCommentingOn" @click="currentlyCommentingOn = null" class="control is-expanded has-background-white-ter">
        <textarea
          class="textarea"
          rows="1"
          :placeholder="`Reply to ${ parent_user_name }`"></textarea>
      </div>
      <div v-show="!currentlyCommentingOn" class="control is-expanded" ref="no">
        <textarea
          rows="1"
          @input="onTextareaInput"
          ref="commentInput"
          maxlength="254"
          :disabled="isSaving"
          class="textarea"
          :placeholder="`Reply to ${ parent_user_name }`"></textarea>
          <b-button icon-left="send" aria-label="Send message" :loading="isSaving" @click.prevent="onSubmit" :disabled="!newCommentValue || currentlyCommentingOn"></b-button>
      </div>
    </div>
  </footer>
</div>

</template>

<script>
var platform = require('platform');

export default {
  props: ['user', 'parent_user_name', 'reply_id', 'lesson_id'],
  data() {
    return {
      isLoaded: false,
      isSaving: false,
      newCommentValue: null,
      currentlyCommentingOn: null,
      errorLoading: false,
      errors: '',
      comments: [
      ]
    }
  },

  mounted() {
    axios({
        method: 'get',
        url: `/lesson/${this.lesson_id}/reply/${this.reply_id}/comments`,
        timeout: 15000
      })
      .then(response => {
        this.comments = response.data;
        this.isLoaded = true;
      })
      .catch(error => {
        this.errorLoading = true;
        axios.post('/log', {'error': `COMMENT GET ERROR\n${ platform.description }\n${ JSON.stringify(error) }`});
      })
  },

  watch: {
    isLoaded() {
      this.$nextTick(function () {
        if(this.$refs.comments) {
          this.$refs.comments.$el.scrollTop = 999999;
        }
      })
    },
    currentlyCommentingOn(value) {
      this.newCommentValue = null;
      if(value) {
        this.$nextTick(function () {
          this.$refs[`commentInput${ value }`][0].focus();
          this.$refs[`commentInput${ value }`][0].scrollIntoView();
        });
      }
      else {
        this.$nextTick(function () {
          this.$refs.commentInput.focus();
        });
      }
    }
  },

  methods: {

    onTextareaInput(event) {
      this.newCommentValue = event.target.value;
      event.target.style.height = "auto";
      event.target.style.height = event.target.scrollHeight + "px";
    },

    onSubmit() {
      this.isSaving = true;

      const data = new FormData();

      data.append('user_id', this.user.id);
      data.append('reply_id', this.reply_id);
      data.append('value', this.newCommentValue);
      if(this.currentlyCommentingOn) {
        data.append('comment_id', this.currentlyCommentingOn);
      }

      axios({
          method: 'post',
          url: `/lesson/${this.lesson_id}/reply/${this.reply_id}/comment`,
          data: data,
          headers: {
            'Content-Type': `multipart/form-data; boundary=${data._boundary}`
          },
          timeout: 90000
        })
        .then(response => {
          this.newCommentValue = null;
          this.$refs.commentInput.value = '';
          this.currentlyCommentingOn = null;
          this.$refs.commentInput.style.height = 'auto';
          setTimeout(()=>{
            this.isSaving = false;
          }, 2000);
          let newComment = response.data;
          newComment.user = this.user;
          if(response.data.comment_id) {
            let parentComment = this.comments.find(element => element.id == response.data.comment_id);
            if(!parentComment.comments) {
              parentComment.comments = [];
            }
            parentComment.comments.push(newComment);
          }
          else {
            this.comments.push(response.data);
            this.$nextTick(function () {
              this.$refs.comments.$el.scrollTop = 999999;
            });
          }
        })
        .catch(error => {
          this.isSaving = false;
          this.$buefy.snackbar.open({
            message: `<b>Error:</b> ${error.data ? error.data.message : error}`,
            type: 'is-danger',
            position: 'is-bottom',
            duration: 5000
          });
          axios.post('/log', {'error': `COMMENT POST ERROR\n${ platform.description }\n${ error.name }: ${ error.message }\nReply data: ${JSON.stringify(data)}\n\n`});
          this.errors = error.response.data.errors || '';
        });
    },

  }
};
</script>

<style lang="scss">
@import "../../sass/variables";

.comment-wrapper {
  display: flex;
  flex-direction: column;
  flex: 100 1 100%;
  position: relative;
}

.comment-list {
  flex-basis: 0;
  padding-top: 0.5em;
  padding-bottom: 0.5em;
  &__empty {
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
}

.comment-foot {
  border-top: none;
  background-color: #fff;
  padding-top: 0;
  padding-bottom: 0.75em;
}

.comment-reply-field {
  width: 100%;

  .control {
    border-radius: 1.5rem;
    margin-left: -0.75rem !important;
    margin-right: -0.75rem !important;
    background-color: $white-ter;
  }
  textarea {
    resize: none;
    width: 100%;
    overflow-y: auto;
    padding-left: 1em;
    padding-right: 2.5rem;
    background-color: transparent;
    max-height: 6.5rem;
    border: none;
    max-width: none;
    box-shadow: none !important;
    outline: none !important;
  }
  button {
    position: absolute;
    right: 0.1rem;
    bottom: 0.2rem;
    border-radius: 99999px;
    border: none !important;
    background-color: transparent !important;
    i {
      font-size: 1.5em;
      color: $success;
    }
  }
}

.comment-card {
  display: flex;
  flex-direction: row;
  padding: 0.5em 0;
  cursor: pointer;

  &--child {
    padding: 0.5em;
    &:last-child {
      margin-bottom: 0.5em;
    }
  }

  &__reply-button {
    opacity: 0;
    font-size: 0.8rem;
    color: $grey;
  }

  &:hover {
    .comment-card__reply-button {
      opacity: 1;
    }
  }

  &__value {
    word-break: break-word;

    li {
      list-style-type: disc;
      margin-left: 1.5em;
    }
    p:last-child {
      margin-bottom: 0;
    }
  }

  .image {
    flex: 0 0 2rem;
    margin-right: 0.25rem;
  }
}

.child-comments {
  margin-left: 0.5rem;
}
</style>
