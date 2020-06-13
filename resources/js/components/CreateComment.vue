<template>

<div class="comment-wrapper">


  <div class="comment-list modal-card-body" v-if="errorLoading">
    <div class="notification">
      An error occured while trying to load comments
    </div>
  </div>

  <transition-group ref="comments" name="list" tag="div" v-else-if="comments.length && isLoaded" class="comment-list modal-card-body">

    <div v-for="comment in comments" class="comment-card" :key="comment.id">
      <figure class="image is-32x32">
        <img class="is-rounded" :src="comment.user.photo" />
      </figure>
      <div>
        <p class="is-size-7 has-text-weight-semibold">{{ comment.user.first_name }} <timeago class="has-text-grey has-text-weight-light" :datetime="comment.created_at" :auto-update="60"></timeago></p>
        <p class="is-size-7">{{ comment.value }}</p>
      </div>
    </div>

  </transition-group>

  <div v-else-if="isLoaded" class="comment-list comment-list__empty modal-card-body">
    <div class="notification">
      Be the first to comment!
    </div>
  </div>

  <div v-else class="comment-list modal-card-body">
    <b-loading :is-full-page="false" :active="!isLoaded"></b-loading>
  </div>

  <footer class="modal-card-foot is-radiusless">
    <div class="field is-grouped">
      <div class="control is-expanded">
        <input ref="commentInput" :disabled="isSaving" v-model="newCommentValue" type="text" class="input" placeholder="Add a comment...">
      </div>
      <div class="control">
        <b-button type="is-primary" :loading="isSaving" @click.prevent="onSubmit">Send</b-button>
      </div>
    </div>
  </footer>
</div>

</template>

<script>
var platform = require('platform');

export default {
  props: ['user', 'reply_id', 'lesson_id'],
  data() {
    return {
      isLoaded: false,
      isSaving: false,
      newCommentValue: null,
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
        timeout: 4000
      })
      .then(response => {
        this.comments = response.data;
        this.isLoaded = true;
      })
      .catch(error => {
        this.errorLoading = true;
      })
  },

  watch: {
    isLoaded() {
      this.$nextTick(function () {
        if(this.$refs.comments) {
          this.$refs.comments.$el.scrollTop = 999999;
        }
      })
    }
  },

  methods: {

    onSubmit() {
      this.isSaving = true;

      const data = new FormData();

      data.append('user_id', this.user.id);
      data.append('reply_id', this.reply_id);
      data.append('value', this.newCommentValue);

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
          setTimeout(()=>{
            this.newCommentValue = null;
            this.isSaving = false;
          }, 2000);
          let newComment = response.data;
          newComment.user = this.user;
          this.comments.push(response.data);
          this.$nextTick(function () {
            this.$refs.comments.$el.scrollTop = 999999;
          });
        })
        .catch(error => {
          this.isSaving = false;
          this.$buefy.snackbar.open({
            message: `<b>Error:</b> ${error.response.data.message}`,
            type: 'is-danger',
            position: 'is-bottom',
            duration: 5000
          });
          axios.post('/log', {'error': `COMMENT FIELD ERROR\n${ platform.description }\n${ JSON.stringify(error) }`});
          this.errors = error.response.data.errors || '';
        });
    },

  }
};
</script>

<style>

.list-enter-active, .list-leave-active {
  transition: all 1.5s;
}

.list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */ {
  opacity: 0;
  transform: translateY(10px);
  background-color: #f7a543;
}

.comment-wrapper {
  display: flex;
  flex-direction: column;
  flex: 100 1 100%;
  position: relative;
}

.comment-list {
  flex-basis: 0;
}

.comment-list__empty {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.comment-card {
  display: flex;
  flex-direction: row;
  padding: 0.5em 0;
}

.comment-card .image {
  flex-shrink: 0;
  margin-right: 0.5em;
}
</style>
